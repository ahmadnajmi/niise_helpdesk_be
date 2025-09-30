<?php

namespace App\Http\Services;

use App\Models\Incident;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class DashboardServices
{
    public function getDashboardData($branchId = null)
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

        

        return [
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
    }
}
