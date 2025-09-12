<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Incident;
use App\Models\SlaTemplate;
use App\Models\RefTable;
use Illuminate\Support\Facades\DB;

class ReportServices
{
    public static function index(){

        $data['to_be_breach'] = self::toBeBreach();
        $data['total_incident'] = self::totalIncident();
        $data['total_incident_status'] = self::totalIncidentStatus();
        $data['outstanding_incident'] = self::outstandingIncident();

        return $data;
    }

    public static function toBeBreach(){
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

    public static function totalIncident(){
        $data = [];
        $get_category = Category::whereDoesntHave('childCategory')->get();

        foreach($get_category as $category){

            $get_incident = Incident::where('category_id',$category->id)->count();

            $format['category'] = $category->name;
            $format['total'] = $get_incident;

            $data[] = $format;            
        }

        return $data;
    }

    public static function totalIncidentStatus(){

        $counts = RefTable::where('code_category','incident_status')
                        ->withCount('incidentsStatus')
                        ->get()
                        ->map(function ($status) {
                            return [
                                'description' => $status->name,
                                'total'       => $status->incidents_status_count, // auto added by withCount
                            ];
                        });

        return $counts;

    }

    public static function outstandingIncident(){
        $data = [];

        $ref_tables = RefTable::where('code_category','severity')->orderBy('ref_code','asc')->get();
        $get_category = Category::select('id','category_id','name')->whereDoesntHave('childCategory')->get();

        $incident_counts = Incident::select('category_id', DB::raw('COUNT(*) as total'))
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
}