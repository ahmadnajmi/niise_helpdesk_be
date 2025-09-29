<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\Incident;
use App\Models\SlaTemplate;
use App\Models\RefTable;
use App\Models\Report;
use Illuminate\Support\Str;

class ReportServices
{
    protected string $baseUrl;

    public function __construct(){
        $this->baseUrl = config('app.microservices.url');
        $this->pathFolder = config('app.microservices.path');
        $this->beUrl = config('app.url');
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

        $file = $report ? $report->file_name : 'unattendedDailyReport';
       
        $fileExtension = $request->report_format == RefTable::PDF ? 'pdf' : 'csv' ;

        $chart_image = $this->uploadDoc($request);

        $parameter  = [
            "SUBREPORT_DIR" => $this->pathFolder.$file.'/',
            "image_path" => $this->beUrl."/logo_immigration.png",
            "chart_image" => $chart_image,
            "cawangan_id" => $request->branch_id,
            "kontraktor_id" => $request->contractor_id,
        ];
        

        $data = [
            'reportTemplate' => $file.'/'.$file.'.jasper',
            'outputFileName' => $file.'.'.$fileExtension,
            'reportTitle' => $request->tittle,
            'report_format' => $fileExtension,
            'parameters' => $parameter
        ];
        
        $generate = $this->callMicroServices('testing/generate','POST',$data);
        
        return $generate;
    }

    public function callMicroServices($api_url,$method,$json) {

        try{
            $response = Http::$method($this->baseUrl.$api_url, $json);
            
            if ($response->successful()) {
                $contentType = $this->getContentType($json['report_format']);
                $filename = $json['outputFileName'];
                
                return [
                    'data' => response($response->body(), 200)->header('Content-Type', $contentType)->header('Content-Disposition', 'inline; filename="'.$filename.'"')
                    
                ]; 
            }
            else{
                
                return ['data' => null ,'status' => $response->status(),'message' => 'Failed to generate report'.$response->body()];
            }
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e){ 
            $message = 'Something went wrong on the server.Error Code = '. $e->getCode();

            Log::channel('external_api')->error("API Response: {$e->getCode()}, {$this->baseUrl}{$api_url}", [
                'message' => $e->getMessage(),
            ]);
            
            return ['data' => null,'status' =>null,'message' => $e->getMessage()];
        }

    }

    private function getContentType($reportFormat) {
        switch($reportFormat) {
            case 'pdf':
                return 'application/pdf';
            case 'csv':
            case 'excel':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            default:
                return 'application/pdf';
        }
    }

    public function uploadDoc($request){
        
        $destination = storage_path('app/public/report'); 

        $file_name = $this->beUrl."/empty.png";

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        if ($request->hasFile('chart_file') && $request->file('chart_file')->isValid()){
            $file = $request->file('chart_file');

            $image_name = time() . '_' . Str::random(10);
            $mimeType = $request->file('chart_file')->getClientOriginalExtension();
            $file_name = $image_name.'.'.$mimeType;

            $file->move($destination, $file_name);

            $fileUrl = asset('storage/report/' . $file_name);
        }
        // $file_name = $this->beUrl."/empty.png";
        
        return $file_name;
    }
}