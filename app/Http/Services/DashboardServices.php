<?php

namespace App\Http\Services;

use App\Models\Incident;
use App\Models\Branch;
use App\Models\Workbasket;
use App\Models\RefTable;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ResponseTrait;

class DashboardServices
{
    use ResponseTrait;

    public static function getDashboardData($branchId = null)
    {
        $incidentQuery = Incident::query();
        if ($branchId) {
            $incidentQuery->where('branch_id', $branchId)->with('branch');
            $currentBranch = Branch::find($branchId)?->name ?? 'Unknown Branch';
        }else {
            // No branch selected → treat as "All Branches"
            $branchId = 'All Branches';
            $currentBranch = 'All Branches';
        }

        
        $allBranches = Branch::select('id', 'name', 'category')->get();
        $trueTotalIncidents = (clone $incidentQuery)->count();

        $moreThan4Days = (clone $incidentQuery)
            ->where('incident_date', '<', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', Incident::OPEN)
            ->count();

        $just4Days = (clone $incidentQuery)
            ->where('incident_date', '=', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', Incident::OPEN)
            ->count();

        $lessThan4Days = (clone $incidentQuery)
            ->where('incident_date', '>', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', Incident::OPEN)
            ->count();

        $New = (clone $incidentQuery)
            ->where('incident_date', '>=', now()->startOfDay()->modify('-5 days'))
            ->count();

        $totalIncidentsThisYear = (clone $incidentQuery)
            ->whereRaw("TO_CHAR(incident_date, 'YYYY') = ?", [now()->year])
            ->count();

        $incidentsByMonth = (clone $incidentQuery)
            ->select(
                DB::raw("EXTRACT(MONTH FROM incident_date) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('incident_date', [now()->startOfYear(), now()->endOfYear()])
            ->groupBy(DB::raw("EXTRACT(MONTH FROM incident_date)"))
            ->pluck('total', 'month')
            ->toArray();

        $allMonths = array_fill(1, 12, 0);
        $incidentsByMonth = array_values(array_replace($allMonths, $incidentsByMonth));

        $incidentsByDay = (clone $incidentQuery)
            ->select(
                DB::raw("EXTRACT(DAY FROM incident_date) as day"),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('incident_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy(DB::raw("EXTRACT(DAY FROM incident_date)"))
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = now()->daysInMonth;
        $allDays = array_fill(1, $daysInMonth, 0);
        $incidentsByDay = array_values(array_replace($allDays, $incidentsByDay));

        $incidentsRaw = (clone $incidentQuery)
            ->selectRaw("TO_CHAR(incident_date, 'HH24') as hour, COUNT(*) as total")
            ->whereBetween('incident_date', [now()->startOfDay(), now()->endOfDay()])
            ->groupBy(DB::raw("TO_CHAR(incident_date, 'HH24')"))
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $allHours = array_fill(0, 24, 0);
        foreach ($incidentsRaw as $h => $cnt) {
            $allHours[(int) $h] = (int) $cnt;
        }
        $incidentsByHour = array_values($allHours);

        $totalIncidentsThisMonth = (clone $incidentQuery)
            ->whereRaw("TO_CHAR(incident_date, 'YYYY') = ?", [now()->year])
            ->whereRaw("TO_CHAR(incident_date, 'MM') = ?", [now()->format('m')])
            ->count();

        $totalIncidentsToday = (clone $incidentQuery)
            ->whereRaw("TRUNC(incident_date) = TRUNC(SYSDATE)")
            ->count();

        $totalIncidentsByBranch = (clone $incidentQuery)
            ->whereNotNull( 'branch_id',)
            ->whereNotNull( 'code_sla',)
            ->where('code_sla', '!=', 0)
            ->selectRaw('branch_id, code_sla, COUNT(*) as total')
            ->groupBy('branch_id', 'code_sla')
            ->with(['branch','sla.slaTemplate.severityDescription'])
            ->get();

        $totalIncidentsByCategory = (clone $incidentQuery)
            ->selectRaw('category_id, code_sla, COUNT(*) as total')
            ->groupBy('category_id', 'code_sla')
            ->with(['categoryDescription.mainCategory','sla.slaTemplate.severityDescription'])
            ->get()
            ->groupBy(function ($row) {
                return $row->categoryDescription->mainCategory->name 
                    ?? $row->categoryDescription->name;
            })
            ->map(function ($rows)  {
                $mainTotal    = $rows->sum('total');
                $mainCritical = $rows->where('sla.slaTemplate.severityDescription.id', 47)->sum('total');

                return [
                    'id' => $rows->first()->categoryDescription?->mainCategory?->id,
                    'main_total'    => $mainTotal,
                    'main_critical' => $mainCritical,
                    'subs' => $rows->groupBy('categoryDescription.name')->map(function ($subRows) {
                        
                        return [
                            'id' => $subRows->first()->category_id,
                            'name'     => $subRows->first()->categoryDescription->name,
                            'total'    => $subRows->sum('total'),
                            'critical' => $subRows->where('sla.slaTemplate.severityDescription.id', 47)->sum('total'),
                        ];
                    })->values()
                ];
            });
        $SeverityOutput = (clone $incidentQuery)
            ->whereBetween('expected_end_date', [
                now()->startOfDay(),          
                now()->addDays(2)->endOfDay()     
            ])
            ->whereIn('status', [Incident::OPEN, Incident::ON_HOLD])
            ->selectRaw('category_id, code_sla, COUNT(*) as total')
            ->groupBy('category_id','code_sla')
            ->with(['categoryDescription','sla.slaTemplate'])
            ->get();

        $IncidentsOpen = (clone $incidentQuery)->where('status', '=', Incident::OPEN)->count();
        $IncidentsDone = (clone $incidentQuery)->where('status', '=', Incident::RESOLVED)->count();

        $onHoldCount = (clone $incidentQuery)->where('status', Incident::ON_HOLD)->count();
        $openCount   = (clone $incidentQuery)->where('status', Incident::OPEN)->count();

        $TBB1 = (clone $incidentQuery)
            ->whereBetween('expected_end_date', [
                now()->startOfDay(),      
                now()->addDays(2)->endOfDay()   
            ])
            ->where('status', Incident::ON_HOLD)->count();

        $TBB2 = (clone $incidentQuery)
            ->whereBetween('expected_end_date', [
                now()->startOfDay(),             
                now()->addDays(2)->endOfDay()  
            ])
            ->where('status', Incident::OPEN)->count();

        $IncidentsOnHold = $onHoldCount + $openCount;
        $TBB = $TBB1 + $TBB2;

        

        $return = [
            'trueTotalIncidents' => $trueTotalIncidents,
            'allBranches' => $allBranches,
            'moreThan4Days' => $moreThan4Days,
            'just4Days' => $just4Days,
            'lessThan4Days' => $lessThan4Days,
            'totalIncidentsThisYear' => $totalIncidentsThisYear,
            'totalIncidentsThisMonth' => $totalIncidentsThisMonth,
            'totalIncidentsToday' => $totalIncidentsToday,
            'incidentsByMonth' => $incidentsByMonth,
            'incidentsByDay' => $incidentsByDay,
            'incidentsByHour' => $incidentsByHour,
            'totalIncidentsByBranch' => $totalIncidentsByBranch,
            'totalIncidentsByCategory' => $totalIncidentsByCategory,
            'SeverityOutput' => $SeverityOutput,
            'IncidentsOpen' => $IncidentsOpen,
            'IncidentsDone' => $IncidentsDone,
            'New' => $New,
            'IncidentsOnHold' => $IncidentsOnHold,
            'TBB' => $TBB,
            'currentBranch' => $currentBranch
        ];

        return self::success('Success', $return);
    }

    public static function index($request){
        
        $return = [
            // 'incident_status' => self::incidentStatus($request),
            // 'incident_four_days' => self::incidentFourDays($request),
            // 'incident_by_branch' => self::incidentByBranch($request),
            // 'incident_by_category' => self::incidentByCategory($request),
            'incident_to_be_breach' => self::incidentToBeBreach($request),
        ];

        return self::success('Success', $return);
    }

    public static function incidentStatus($request){

        $data['total_incident'] = Incident::count();
        $data['in_progress'] = Incident::whereHas('workbasket', function ($query)use($data) {
                                            $query->where('status',Workbasket::IN_PROGRESS);
                                        })
                                        ->count();

        $data['new'] = Incident::where('status',Incident::OPEN)->count();
        $data['resolved'] = Incident::where('status',Incident::RESOLVED)->count();
        $data['closed'] = Incident::where('status',Incident::CLOSED)->count();

        return $data;
    }
    
    public static function incidentFourDays($request){

        $data['more_day'] = Incident::whereDate('expected_end_date', '<', now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->count();

        $data['less_day'] = Incident::whereDate('expected_end_date', '>', now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->count();

        $data['same_day'] = Incident::whereDate('expected_end_date',now()->subDays(4)->startOfDay())
                                    ->where('status', '=', Incident::OPEN)
                                    ->count();
        return $data;
    }

    public static function incidentByBranch($request){
        $get_branch = Branch::select('id','name')
                            ->when($request->branch_id, function ($query) use ($request) {
                                return $query->where('id',$request->branch_id); 
                            })
                            ->get();

        $total_incident = Incident::query()->selectRaw('branch_id, COUNT(*) as total_incident')->groupBy('branch_id');

        $critical_incident = Incident::query()->selectRaw('branch_id, COUNT(*) as total_incident_critical')
                                    ->whereHas('sla.slaTemplate',function ($query){
                                        $query->where('severity_id', RefTable::SEVERITY_CRITICAL);
                                    })
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

    // public static function incidentByCategory($request){
    //     $get_category = Category::select('id','category_id','name')
    //                         ->orderBy('category_id','desc')
    //                         ->orderBy('name','asc')
    //                         ->get();

    //     $total_incident = Incident::query()->selectRaw('category_id, COUNT(*) as total_incident')->groupBy('category_id');

    //     $critical_incident = Incident::query()->selectRaw('category_id, COUNT(*) as total_incident_critical')
    //                                 ->whereHas('sla.slaTemplate',function ($query){
    //                                     $query->where('severity_id', RefTable::SEVERITY_CRITICAL);
    //                                 })
    //                                 ->groupBy('category_id');

    //     $total_incident = $total_incident->pluck('total_incident', 'category_id');
    //     $critical_incident = $critical_incident->pluck('total_incident_critical', 'category_id');

    //     $result = [];

    //     foreach ($get_category as $category) {
    //         $result[$category->id] = [
    //             'category_id' => $category->id,
    //             'category_name' => $category->name,
    //             'total_incident' => $total_incident[$category->id] ?? 0,
    //             'total_incident_critical' => $critical_incident[$category->id] ?? 0,
    //             'children' => [],
    //             'parent_id' => $category->category_id,
    //         ];
    //     }

    //     $tree = [];

    //     foreach ($result as $id => &$node) {
    //         if ($node['parent_id']) {
    //             $result[$node['parent_id']]['children'][] = &$node;
    //         } else {
    //             $tree[] = &$node; 
    //         }
    //     }
    //     unset($node);

    //     foreach ($result as &$node) {
    //         unset($node['parent_id']);
    //     }

    //     return array_values($tree);

    // }

    public static function incidentByCategory($request){
        $categories = Category::select('id', 'category_id', 'name')
                                ->orderBy('category_id', 'desc')
                                ->orderBy('name', 'asc')
                                ->get();

        $incidentCounts = Incident::selectRaw('category_id, COUNT(*) as total_incident')
                                ->groupBy('category_id')
                                ->pluck('total_incident', 'category_id');

        $criticalCounts = Incident::selectRaw('category_id, COUNT(*) as total_incident_critical')
                                    ->whereHas('sla.slaTemplate', function ($q) {
                                        $q->where('severity_id', RefTable::SEVERITY_CRITICAL);
                                    })
                                    ->groupBy('category_id')
                                    ->pluck('total_incident_critical', 'category_id');

        $items = [];
        foreach ($categories as $cat) {
            $own = $incidentCounts[$cat->id] ?? 0;
            $ownCritical = $criticalCounts[$cat->id] ?? 0;
            
            $items[$cat->id] = [
                'category_id' => $cat->id,
                'category_name' => $cat->name,
                'total_incident' => $own,
                'total_incident_critical' => $ownCritical,
                'children' => [],
                '_own' => $own,
                '_own_critical' => $ownCritical,
                '_parent' => $cat->category_id,
            ];
        }

        $tree = [];

        foreach ($items as $id => &$node) {
            if ($node['_parent'] && isset($items[$node['_parent']])) {
                $items[$node['_parent']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }
        unset($node);

        $aggregate = function (&$node) use (&$aggregate) {
            $childTotal = 0;
            $childCritical = 0;
            
            foreach ($node['children'] as &$child) {
                $aggregate($child);
                $childTotal += $child['total_incident'];
                $childCritical += $child['total_incident_critical'];
            }
            unset($child);
            
            $node['total_incident'] = $node['_own'] + $childTotal;
            $node['total_incident_critical'] = $node['_own_critical'] + $childCritical;
            
            unset($node['_own'], $node['_own_critical'], $node['_parent']);
        };

        foreach ($tree as &$root) {
            $aggregate($root);
        }

        unset($root);

        return array_values($tree);
    }

    public static function incidentToBeBreach($request){
        $allCategories = Category::select('id', 'category_id', 'name')
                                ->orderBy('category_id', 'desc')
                                ->orderBy('name', 'asc')
                                ->get();

        $rootCategories = $allCategories->whereNull('category_id');

        $dateRange = [now()->startOfDay(), now()->addDays(2)->endOfDay()];

        $counts = Incident::selectRaw('sla_templates.severity_id, incidents.category_id, COUNT(*) as total')
                            ->join('sla', 'sla.id', '=', 'incidents.sla_template_id')
                            ->join('sla_templates', 'sla_templates.id', '=', 'incidents.sla_template_id')
                            ->where('incidents.status', Incident::OPEN)
                            ->whereBetween('incidents.expected_end_date', $dateRange)
                            ->groupBy('sla_templates.severity_id', 'incidents.category_id')
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

        $data = $rootCategories->map(function ($category) use($getAllDescendants,$criticalCounts,$importantCounts,$mediumCounts) {
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

        return $data->values();
    }
}
