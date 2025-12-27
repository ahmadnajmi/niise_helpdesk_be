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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiTrait;

class ReportServices
{
    use ApiTrait;
    public function __construct(){
        // $this->beUrl = config('app.url');
        $this->beUrl = '/var/www/html/helpdesk/jasper_report/reports';
    }

    public static function index($request){

        $data['to_be_breach'] = self::toBeBreach($request);
        $data['total_incident'] = self::totalIncident($request);
        $data['total_incident_status'] = self::totalIncidentStatus($request);
        $data['outstanding_incident'] = self::outstandingIncident($request);

        return $data;
    }

    public static function toBeBreach($request){
        $data = [];
        $get_category = Category::select('id','category_id','name')->whereDoesntHave('childCategory')->get();

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
                                        return $query->where('group_id',$request->contractor_id);
                                    })
                                    ->where('category_id',$category->id)
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

    public static function totalIncident($request){
        $data = [];
        $get_category = Category::whereDoesntHave('childCategory')->get();

        foreach($get_category as $category){

            $get_incident = Incident::where('category_id',$category->id)
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('branch_id',$request->branch_id);
                                    })
                                    ->when($request->contractor_id, function ($query)use($request) {
                                        return $query->where('group_id',$request->contractor_id);
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
                                $query->where('group_id', $request->contractor_id);
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
        $get_category = Category::select('id','category_id','name')->whereDoesntHave('childCategory')->get();

        $incident_counts = Incident::select('category_id', DB::raw('COUNT(*) as total'))
                                    ->when($request->branch_id, function ($query)use($request) {
                                        return $query->where('branch_id',$request->branch_id);
                                    })
                                    ->when($request->contractor_id, function ($query)use($request) {
                                        return $query->where('group_id',$request->contractor_id);
                                    })
                                    ->groupBy('category_id')
                                    ->pluck('total', 'category_id'); 

        foreach($ref_tables as $idx => $reference){

            $format['name'] = 'Severity '.$reference->ref_code; 
            $format['level'] = $reference->ref_code;
            $format['categories'] = [];

            foreach($get_category as $category){
                $format_categories['name'] = $category->name;
                $format_categories['count'] = $incident_counts[$category->id] ?? 0;

                $format['categories'][] = $format_categories;
            }
            $data[] = $format;
        }

        return $data;
    }

    public function generateReport($request){

        $report = Report::where('code',$request->report_category)->first();

        $file = $report ? $report->file_name : 'outstanding';
       
        $fileExtension = $request->report_format == RefTable::PDF ? 'pdf' : 'csv' ;

        $chart_image = $this->uploadDoc($request);

        $parameter  = [
            "logo_background" => public_path("background.png"),
            "logo_tittle" => public_path("logo_immigration.png"),
            "user_name" => Auth::user()->name,
        ];

        if($chart_image && $request->report_category != 'TO_BREACH' && $request->report_category != 'STATUS'){
            $parameter['graph_picture'] = $chart_image;
        }

        $data = [
            'reportTemplate' => $file.'/'.$file.'.jasper',
            'outputFileName' => $report->file_name.'.'.$fileExtension,
            'report_format' => $fileExtension == 'csv' ? 'excel' : 'pdf',
            'parameters' => $parameter
        ]; 
        $generate = self::callApi('jasper','reports/generate','POST',$data);
        
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