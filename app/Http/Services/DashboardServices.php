<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ResponseTrait;
use App\Models\Incident;
use App\Models\Branch;
use App\Models\Workbasket;
use App\Models\RefTable;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Role;
use App\Models\Group;

class DashboardServices
{
    use ResponseTrait;

    public static function index($request){
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit : 15;

        $role = User::getUserRole(Auth::user()->id);

        if($role?->role == Role::JIM){
            $request->merge([
                'branch_id' => Auth::user()->branch_id
            ]);
        }
        
        if($request->code == 'by_branch'){
            $data = self::incidentByBranch($request);
        }
        elseif($request->code == 'by_category'){
            $data = self::incidentByCategory($request);  
        }
        elseif($request->code == 'by_severity'){
            $data = self::incidentToBeBreach($request);
        }
        else{
            $data = self::incidentByContractor($request);
        }

        $paginated = new LengthAwarePaginator(
            $data->forPage($page, $limit)->values(),
            $data->count(),
            $limit,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
            
        $paginated->setCollection($paginated->getCollection()->values());

        return $paginated;
    }

    public static function getDashboardGraph($request){
        $role = User::getUserRole(Auth::user()->id);

        if($role?->role == Role::JIM){
            $request->merge([
                'branch_id' => Auth::user()->branch_id
            ]);
        }

        $daily = self::graphTotalIncidentDaily($request);
        $montly = self::graphTotalIncidentMonthly($request);

        $return = [
            'graph_incident_daily' => $daily,
            'total_incident_daily' =>  array_sum($daily),
            'graph_incident_monthly' => $montly,
            'total_incident_monthly' => array_sum($montly),
            // 'incident_status' => self::incidentStatus($request),
            'incident_four_days' => self::incidentFourDays($request),
        ];

        return self::success('Success', $return);
    }

    public static function incidentStatus($request){

        $data['total_incident'] = Incident::applyFilters($request)->count();

        $data['in_progress'] = Incident::whereHas('workbasket', function ($query)use($data) {
                                            $query->where('status',Workbasket::IN_PROGRESS);
                                        })
                                        ->applyFilters($request)
                                        ->count();

        $data['new'] = Incident::where('status',Incident::OPEN)
                                ->applyFilters($request)
                                ->count();

        $data['resolved'] = Incident::where('status',Incident::RESOLVED)
                                    ->applyFilters($request)
                                    ->count();

        $data['closed'] = Incident::where('status',Incident::CLOSED)
                                    ->applyFilters($request)
                                    ->count();

        return $data;
    }
    
    public static function incidentFourDays($request){

        $data['more_day'] = Incident::whereDate('expected_end_date', '<=', now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->applyFilters($request)
                                    ->count();

        $data['less_day'] = Incident::whereDate('expected_end_date', '>', now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->applyFilters($request)
                                    ->count();

        $data['same_day'] = Incident::whereDate('expected_end_date',now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->applyFilters($request)
                                    ->count();
        return $data;
    }

    public static function incidentByBranch($request){

        $role = User::getUserRole(Auth::user()->id);

        $get_branch = Branch::select('id','name')
                            ->when($request->branch_id, function ($query) use ($request) {
                                return $query->where('id',$request->branch_id); 
                            })
                            ->when(!$request->branch_id, function ($query) use ($request) {
                                return $query->whereHas('incidents', function ($query) use ($request) {
                                    $query->applyFilters($request); 
                                }); 
                            })
                            ->get();

        $total_incident = Incident::query()
                                ->selectRaw('branch_id, COUNT(*) as total_incident')
                                ->applyFilters($request)
                                ->groupBy('branch_id');

        $critical_incident = Incident::query()->selectRaw('branch_id, COUNT(*) as total_incident_critical')
                                    ->whereHas('sla.slaTemplate',function ($query){
                                        $query->where('severity_id', RefTable::SEVERITY_CRITICAL);
                                    })
                                    ->applyFilters($request)
                                    ->groupBy('branch_id');

        $total_incident = $total_incident->pluck('total_incident', 'branch_id');
        $critical_incident = $critical_incident->pluck('total_incident_critical', 'branch_id');


        $data = $get_branch->map(function ($branch) use ($total_incident, $critical_incident) {
            return [
                'branch_id' => $branch->id,
                'branch_name' => $branch->name,
                'total_incident' => $total_incident[$branch->id] ?? 0,
                'total_incident_critical' => $critical_incident[$branch->id] ?? 0,
            ];
        });

        return $data;

    }

    public static function incidentToBeBreach($request){
        $dateRange = [now()->startOfDay(), now()->addDays(4)->endOfDay()];

        $allCategories = Category::select('id', 'category_id', 'name')
                                ->whereHas('incidents', function ($query) use ($request, $dateRange) {
                                    $query
                                        ->where('status', Incident::OPEN)
                                        ->whereBetween('expected_end_date', $dateRange)
                                        // ->whereHas('sla.slaTemplate', function ($query) {
                                        //     $query->whereIn('severity_id', [RefTable::SEVERITY_CRITICAL,RefTable::SEVERITY_IMPORTANT,RefTable::SEVERITY_MEDIUM]);
                                        // })
                                        ->applyFilters($request); 
                                })                            
                                ->orderBy('category_id', 'desc')
                                ->orderBy('name', 'asc')
                                ->get();


        $counts = Incident::selectRaw('sla_template.severity_id as severity_id, incidents.category_id as category_id, COUNT(*) as total')
                            ->join('sla', 'sla.code', '=', 'incidents.code_sla')
                            ->join('sla_template', 'sla_template.id', '=', 'sla.sla_template_id')
                            ->where('incidents.status', Incident::OPEN)
                            ->whereBetween('incidents.expected_end_date', $dateRange)
                            ->whereIn('sla_template.severity_id', [
                                RefTable::SEVERITY_CRITICAL,
                                RefTable::SEVERITY_IMPORTANT,
                                RefTable::SEVERITY_MEDIUM
                            ])
                            ->applyFilters($request)
                            ->groupBy('sla_template.severity_id', 'incidents.category_id')
                            ->get();

        $criticalCounts  = $counts->where('severity_id', RefTable::SEVERITY_CRITICAL)->pluck('total', 'category_id');
        $importantCounts = $counts->where('severity_id', RefTable::SEVERITY_IMPORTANT)->pluck('total', 'category_id');
        $mediumCounts    = $counts->where('severity_id', RefTable::SEVERITY_MEDIUM)->pluck('total', 'category_id');

        $categoryMap = $allCategories->keyBy('id');
        
        $childrenMap = $allCategories->groupBy('category_id');

        $getAllDescendants = function($categoryId) use (&$getAllDescendants, $childrenMap) {
            $descendants = [];
            $children = $childrenMap->get($categoryId, collect());
            
            foreach ($children as $child) {
                $descendants[] = $child->id;
                // Recursively get grandchildren
                $descendants = array_merge($descendants, $getAllDescendants($child->id));
            }
            
            return $descendants;
        };
        
        $data = $allCategories->map(function ($category) use($getAllDescendants,$criticalCounts,$importantCounts,$mediumCounts) {
            $descendantIds = $getAllDescendants($category->id);
            
            $critical = 0;
            $important = 0;
            $medium = 0;
            
            foreach ($descendantIds as $descendantId) {
                $critical += $criticalCounts[$descendantId] ?? 0;
                $important += $importantCounts[$descendantId] ?? 0;
                $medium += $mediumCounts[$descendantId] ?? 0;
            }
            
            return [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'total_incident_critical' => $critical,
                'total_incident_important' => $important,
                'total_incident_medium' => $medium,
            ];
        });

        return $data->filter(function($item) {
            return $item['total_incident_critical'] > 0 
                || $item['total_incident_important'] > 0 
                || $item['total_incident_medium'] > 0;
        })->values();

        return $data->values();
    }

    public static function incidentByContractor($request){
        $role = User::getUserRole(Auth::user()->id);

        $get_group = Group::select('id','name')
                            ->when($role?->role == Role::CONTRACTOR, function ($query) use ($request){
                                $query->whereHas('userGroup', function ($query) {
                                    $query->where('user_id', Auth::user()->id);
                                });
                            })
                            ->whereHas('incidents')
                            ->get();

        $data = [];

        foreach($get_group as $group){
            $incidentCounts = Incident::applyFilters($request) ->where('assign_group_id', $group->id);

            $total_incident = $incidentCounts->count();

            $critical_incident = $incidentCounts->whereHas('sla', function ($query)use($request) {
                                                    $query->whereHas('slaTemplate', function ($query)use($request) {
                                                        $query->where('severity_id',$request->severity_id); 
                                                    }); 
                                                })
                                                ->count();

            $data[] = [
                'id' => $group->id,
                'name' => $group->name,
                'total_incident' => $total_incident,
                'total_incident_critical' => $critical_incident,
            ];
        }


        return collect($data);

    }

    public static function graphTotalIncidentDaily($request){

        $incidentsByDay = Incident::select(
                                        DB::raw("EXTRACT(DAY FROM incident_date) as day"),
                                        DB::raw('COUNT(*) as total')
                                    )
                                    ->whereBetween('incident_date', [now()->startOfMonth(), now()->endOfMonth()])
                                    ->applyFilters($request)
                                    ->groupBy(DB::raw("EXTRACT(DAY FROM incident_date)"))
                                    ->pluck('total', 'day')
                                    ->toArray();

        $daysInMonth = now()->daysInMonth;
        $allDays = array_fill(1, $daysInMonth, 0);
        $incidentsByDay = array_values(array_replace($allDays, $incidentsByDay));

        return $incidentsByDay;
    }

    public static function graphTotalIncidentMonthly($request){
        
        $incidentsByMonth = Incident::select(
                                DB::raw("EXTRACT(MONTH FROM incident_date) as month"),
                                DB::raw('COUNT(*) as total')
                            )
                            ->whereBetween('incident_date', [now()->startOfYear(), now()->endOfYear()])
                            ->applyFilters($request)
                            ->groupBy(DB::raw("EXTRACT(MONTH FROM incident_date)"))
                            ->pluck('total', 'month')
                            ->toArray();

        $allMonths = array_fill(1, 12, 0);
        $incidentsByMonth = array_values(array_replace($allMonths, $incidentsByMonth));

        return $incidentsByMonth;
    }

    public static function countIncidentCategory($request,$category_id){
        
        $total_incident = Incident::whereIn('category_id',$category_id)
                                    ->applyFilters($request)
                                    ->count();

        $total_incident_critical = Incident::whereHas('sla.slaTemplate', function ($q) {
                                            $q->where('severity_id', RefTable::SEVERITY_CRITICAL);
                                        })
                                        ->applyFilters($request)
                                        ->whereIn('category_id',$category_id)
                                        ->count();

        return [
            'total_incident' => $total_incident,
            'total_incident_critical' => $total_incident_critical,
        ];
    }

    public static function incidentByCategory($request){
        $allCategories = Category::select('id', 'category_id', 'name')
                                // ->whereHas('incidents', function ($query) use ($request) {
                                //     $query->applyFilters($request); 
                                // })     
                                ->get()
                                ->keyBy('id');

                                $categoryHierarchy = self::buildCategoryHierarchy($allCategories);

        $allCategoryIds = $allCategories->pluck('id')->toArray();

        $incidentCounts = self::bulkCountIncidents($request, $allCategoryIds, $categoryHierarchy);

        $data = self::buildHierarchicalData($allCategories, $incidentCounts);

        return collect($data);
    }   

    private static function buildHierarchicalData($allCategories, $incidentCounts, $parentId = null, $level = 0){
        $data = [];
        $categories = $allCategories->where('category_id', $parentId)->sortBy('name');

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->id,
                'category_id' => $category->category_id,
                'category_name' => $category->name,
                'level' => $level, 
                'total_incident' => $incidentCounts['total'][$category->id] ?? 0,
                'total_incident_critical' => $incidentCounts['critical'][$category->id] ?? 0,
            ];

            $children = self::buildHierarchicalData($allCategories, $incidentCounts, $category->id, $level + 1);
            $data = array_merge($data, $children);
        }

        return collect($data)
                ->filter(function($item) {
                    return $item['total_incident'] > 0;
                })
                ->values()
                ->toArray();

        return $data;
    }


    private static function buildCategoryHierarchy($categories){
        $hierarchy = collect();
        $childrenMap = $categories->groupBy('category_id');

        foreach ($categories as $category) {
            $descendants = collect([$category->id]);
            $toProcess = collect([$category->id]);

            while ($toProcess->isNotEmpty()) {
                $currentId = $toProcess->shift();
                $children = $childrenMap->get($currentId, collect());
                
                foreach ($children as $child) {
                    if (!$descendants->contains($child->id)) {
                        $descendants->push($child->id);
                        $toProcess->push($child->id);
                    }
                }
            }

            $hierarchy->put($category->id, $descendants);
        }

        return $hierarchy;
    }


    private static function bulkCountIncidents($request, array $categoryIds, $categoryHierarchy){
        $rawTotalIncidents = Incident::selectRaw('category_id, COUNT(*) as count')
                                        ->whereIn('category_id', $categoryIds)
                                        ->applyFilters($request)
                                        ->groupBy('category_id')
                                        ->pluck('count', 'category_id')
                                        ->toArray();

        $rawCriticalIncidents = Incident::selectRaw('category_id, COUNT(*) as count')
                                        ->whereHas('sla.slaTemplate', function ($q) {
                                            $q->where('severity_id', RefTable::SEVERITY_CRITICAL);
                                        })
                                        ->whereIn('category_id', $categoryIds)
                                        ->applyFilters($request)
                                        ->groupBy('category_id')
                                        ->pluck('count', 'category_id')
                                        ->toArray();

        $totalIncidents = [];
        $criticalIncidents = [];

        foreach ($categoryHierarchy as $categoryId => $descendantIds) {
            $totalIncidents[$categoryId] = 0;
            $criticalIncidents[$categoryId] = 0;

            foreach ($descendantIds as $descId) {
                $totalIncidents[$categoryId] += $rawTotalIncidents[$descId] ?? 0;
                $criticalIncidents[$categoryId] += $rawCriticalIncidents[$descId] ?? 0;
            }
        }

        return [
            'total' => $totalIncidents,
            'critical' => $criticalIncidents,
        ];
    }

}
