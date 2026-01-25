<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Incident;
use App\Models\SlaTemplate;
use App\Models\RefTable;
use App\Models\Report;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiTrait;
use App\Models\LogExternalApi;

class ReportServices
{
    use ApiTrait;
 
    public static function index($request){

        $role = User::getUserRole(Auth::user()->id);

        if($role?->role == Role::CONTRACTOR){
            $request->merge([
                'contractor_id' => Auth::user()->company_id
            ]);
        }

        $data['to_be_breach'] = self::toBeBreach($request);
        $data['total_incident'] = self::idleReport($request);
        $data['total_incident_status'] = self::totalIncidentStatus($request);
        $data['outstanding_incident'] = self::outstandingIncident($request);

        return $data;
    }

    public static function toBeBreach($request){
        $data = [];
        $get_category = Category::select('id','category_id','name')
                                // ->whereDoesntHave('childCategory')
                                ->whereHas('incidents', function ($query) use ($request){
                                    $query->where('status',Incident::OPEN)
                                            ->whereDate('expected_end_date', '<=', now()->addDays(4))
                                            ->whereDate('expected_end_date', '>', now())
                                            ->when($request->branch_id, function ($query)use($request) {
                                                return $query->where('branch_id',$request->branch_id);
                                            })
                                            ->when($request->contractor_id, function ($query)use($request) {
                                                return $query->where('assign_group_id',$request->contractor_id);
                                            });
                                })
                                ->get();

        $severity = [SlaTemplate::SEVERITY_CRITICAL,SlaTemplate::SEVERITY_IMPORTANT,SlaTemplate::SEVERITY_MEDIUM];

        foreach($get_category as $category){

            $default = collect([
                'sev_1' => 0,
                'sev_2' => 0,
                'sev_3' => 0,
            ]);

            $get_incident = Incident::with('sla.slaTemplate')
                                    ->whereHas('sla', function ($query)use($severity) {
                                        $query->whereHas('slaTemplate', function ($query)use($severity) {
                                            $query->whereIn('severity_id',$severity);});
                                    })
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('branch_id',$request->branch_id);
                                    })
                                    ->when($request->contractor_id, function ($query)use($request) {
                                        return $query->where('assign_group_id',$request->contractor_id);
                                    })
                                    ->where('category_id',$category->id)
                                    ->where('status',Incident::OPEN)
                                    ->whereDate('expected_end_date', '<=', now()->addDays(4))
                                    ->whereDate('expected_end_date', '>', now())
                                    ->get();

            $counts = $get_incident->groupBy('sla.slaTemplate.severity_id')->map->count();

            $format = $default->merge($get_incident->groupBy('sla.slaTemplate.severity_id')
                            ->map->count()
                            ->mapWithKeys(fn ($count, $id) => ['sev_' . $id => $count]));
        
            $format['category'] = $category->name;

            $data[] = $format;            
        }

        return $data;
    }

    public static function idleReport($request){
        $data = [];
        $get_category = Category::whereHas('incidents', function ($query) use ($request) {
                                    $query->whereIn('status',[Incident::OPEN,Incident::TEMPORARY_FIX,Incident::RESOLVED,Incident::ON_HOLD])
                                            ->whereHas('incidentResolutionLatest', function ($query) use ($request) {
                                                    $query->whereDate('updated_at', '<', now()->subDays(4));
                                            })
                                            ->when($request->branch_id, function ($query)use($request) {
                                                return $query->where('branch_id',$request->branch_id);
                                            })
                                            ->when($request->contractor_id, function ($query)use($request) {
                                                return $query->where('assign_group_id',$request->contractor_id);
                                            });
                                })
                                ->get();

        foreach($get_category as $category){

            $get_incident = Incident::where('category_id',$category->id)
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('branch_id',$request->branch_id);
                                    })
                                    ->when($request->contractor_id, function ($query)use($request) {
                                        return $query->where('assign_group_id',$request->contractor_id);
                                    })
                                    ->whereIn('status',[Incident::OPEN,Incident::TEMPORARY_FIX,Incident::RESOLVED,Incident::ON_HOLD])
                                    ->whereHas('incidentResolutionLatest', function ($query) use ($request) {
                                        $query->whereDate('updated_at', '<', now()->subDays(4));
                                    })
                                    ->count();

            $format['category'] = $category->name;
            $format['total'] = $get_incident;

            $data[] = $format;            
        }

        return $data;
    }

    public static function totalIncidentStatus($request){

        $counts = RefTable::where('code_category','incident_status')
                        // ->withCount('incidentsStatus')
                        ->withCount(['incidentsStatus as total' => function ($query) use ($request) {
                            if ($request->branch_id) {
                                $query->where('branch_id', $request->branch_id);
                            }
                            if ($request->contractor_id) {
                                $query->where('assign_group_id', $request->contractor_id);
                            }
                        }])
                        ->get()
                        ->map(function ($status) {
                            return [
                                'description' => $status->name,
                                'total'       => $status->total, // auto added by withCount
                            ];
                        });

        return $counts;

    }

    public static function outstandingIncident($request){
        $data = [];

        $ref_tables = RefTable::where('code_category','severity')->orderBy('ref_code','asc')->get();
        $get_category = Category::select('id','category_id','name')
                                ->whereDoesntHave('childCategory')
                                ->whereHas('incidents', function ($query) use ($request) {
                                    $query->where('status',Incident::OPEN)
                                        ->when($request->branch_id, function ($query)use($request) {
                                            return $query->where('branch_id',$request->branch_id);
                                        })
                                        ->when($request->contractor_id, function ($query)use($request) {
                                            return $query->where('assign_group_id',$request->contractor_id);
                                        });
                                })
                                ->get();

        $incident_counts = Incident::select('incidents.category_id', 'sla_template.severity_id', DB::raw('COUNT(*) as total'))
                                    ->join('sla', 'incidents.code_sla', '=', 'sla.code')  // Adjust column name if needed
                                    ->join('sla_template', 'sla.sla_template_id', '=', 'sla_template.id')  // Adjust column name if needed
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('incidents.branch_id',$request->branch_id);
                                    })
                                    ->when($request->contractor_id, function ($query)use($request) {
                                        return $query->where('incidents.assign_group_id',$request->contractor_id);
                                    })
                                    ->where('incidents.status',Incident::OPEN)
                                    ->groupBy('incidents.category_id', 'sla_template.severity_id')
                                    ->get()
                                    ->groupBy('severity_id');

        foreach($ref_tables as $idx => $reference){
            
            $format['name'] = 'Severity '.$reference->ref_code; 
            $format['level'] = $reference->ref_code;
            $format['categories'] = [];

            $severity_counts = $incident_counts->get($reference->ref_code, collect())->pluck('total', 'category_id');  

            foreach($get_category as $category){
                $format_categories['name'] = $category->name;
                $format_categories['count'] = $severity_counts[$category->id] ?? 0;

                $format['categories'][] = $format_categories;
            }
            $data[] = $format;
        }

        return $data;
    }

    public function generateReport($request){

        $report = Report::where('code',$request->report_category)->first();
       
        $fileExtension = $request->report_format == RefTable::PDF ? 'pdf' : 'csv' ;

        $chart_image = $this->uploadDoc($request);

        $parameter  = [
            // "logo_background" => public_path("background.png"),
            "logo_tittle" => public_path("logo_immigration.png"),
            "user_name" => Auth::user()->name,
        ];

        $parameter = array_merge($request->only(['start_date', 'end_date','close_start_date','close_end_date','branch_id','severity_id','status','state_id','company_id']), $parameter);

        if($chart_image && $report->code != 'TO_BREACH'){
            $parameter['graph_picture'] = $chart_image;
        }

        $jasperPath = storage_path('app/private/'.$report->path); 

        $output_file_name = $report->output_name ? $report->output_name : $report->jasper_file_name;

        $data = [
            [
                'name'     => 'reportTemplate',
                'contents' => fopen($jasperPath, 'r'),
                'filename' => $report->file_name,
            ],
            [
                'name'     => 'outputFileName',
                'contents' => $output_file_name.'.'.$fileExtension,
            ],
            [
                'name'     => 'report_format',
                'contents' => $fileExtension == 'csv' ? 'excel' : 'pdf',
            ],
            [
                'name'     => 'parameters',
                'contents' => json_encode($parameter),
            ],
        ]; 

        $generate = self::callApi(LogExternalApi::JASPER,'reports/generate','POST',$data);
        
        return $generate;
    }

    public function uploadDoc($request){
        
        $destination = storage_path('app/public/report'); 

        $file_name = null;

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        if ($request->hasFile('chart_file') && $request->file('chart_file')->isValid()){
            $file = $request->file('chart_file');

            $image_name = time() . '_' . Str::random(10);
            $mimeType = $file->getClientOriginalExtension();
            $file_name = $image_name.'.'.$mimeType;

            $fileContents = file_get_contents($file->getRealPath());
        
            file_put_contents($destination . '/' . $file_name, $fileContents);

            $file_name = public_path('storage/report/' . $file_name);
        }        
        return $file_name;
    }
}