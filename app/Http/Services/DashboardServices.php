<?php

namespace App\Http\Services;

use App\Models\Incident;
use App\Models\Sla;
use Illuminate\Support\Facades\DB;

class DashboardServices
{
    public function getDashboardData()
    {
        $trueTotalIncidents = Incident::count();
        $totalSLA = Sla::count();
        $totalReports = Incident::whereNotNull('report_no')->count();

        $moreThan4Days = Incident::where('incident_date', '<', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', 1)
            ->count();

        $just4Days = Incident::where('incident_date', '=', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', 1)
            ->count();

        $lessThan4Days = Incident::where('incident_date', '>=', now()->startOfDay()->modify('-4 days'))
            ->where('status', '=', 1)
            ->count();

        $totalIncidentsThisYear = Incident::whereRaw("TO_CHAR(incident_date, 'YYYY') = ?", [now()->year])
            ->count();

        $incidentsByMonth = Incident::select(
                DB::raw("EXTRACT(MONTH FROM incident_date) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('incident_date', [now()->startOfYear(), now()->endOfYear()])
            ->groupBy(DB::raw("EXTRACT(MONTH FROM incident_date)"))
            ->pluck('total', 'month')
            ->toArray();

        $allMonths = array_fill(1, 12, 0);
        $incidentsByMonth = array_replace($allMonths, $incidentsByMonth);
        $incidentsByMonth = array_values($incidentsByMonth);

        $incidentsByDay = Incident::select(
                DB::raw("EXTRACT(DAY FROM incident_date) as day"),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('incident_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy(DB::raw("EXTRACT(DAY FROM incident_date)"))
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = now()->daysInMonth;
        $allDays = array_fill(1, $daysInMonth, 0);
        $incidentsByDay = array_replace($allDays, $incidentsByDay);
        $incidentsByDay = array_values($incidentsByDay);

        $incidentsRaw = Incident::selectRaw("TO_CHAR(incident_date, 'HH24') as hour, COUNT(*) as total")
            ->whereBetween('incident_date', [now()->startOfDay(), now()->endOfDay()])
            ->groupBy(DB::raw("TO_CHAR(incident_date, 'HH24')"))
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $allHours = array_fill(0, 24, 0);
        foreach ($incidentsRaw as $h => $cnt) {
            $allHours[(int)$h] = (int)$cnt;
        }
        $incidentsByHour = array_values($allHours);

        $totalIncidentsThisMonth = Incident::whereRaw("TO_CHAR(incident_date, 'YYYY') = ?", [now()->year])
            ->whereRaw("TO_CHAR(incident_date, 'MM') = ?", [now()->format('m')])
            ->count();

        $totalIncidentsToday = Incident::whereRaw("TRUNC(incident_date) = TRUNC(SYSDATE)")
            ->count();

        $totalIncidentsByBranch = Incident::selectRaw('branch_id, code_sla, COUNT(*) as total')
            ->groupBy('branch_id','code_sla')
            ->with(['branch','sla.slaTemplate'])
            ->get();

        $totalIncidentsByCategory = Incident::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->with(['categoryDescription','sla.slaTemplate'])
            ->get();

        $SeverityOutput = Incident::selectRaw('category_id, code_sla, COUNT(*) as total')
            ->groupBy('category_id','code_sla')
            ->with(['categoryDescription','sla.slaTemplate'])
            ->get();

        $IncidentsOpen = Incident::where('status', '=', Incident::OPEN)->count();
        $IncidentsDone = Incident::where('status', '=', Incident::RESOLVED)->count();

        return [
            'trueTotalIncidents' => $trueTotalIncidents,
            'totalSLA' => $totalSLA,
            'totalReports' => $totalReports,
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
        ];
    }
}
