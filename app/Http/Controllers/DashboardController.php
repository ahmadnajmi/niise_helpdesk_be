<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\Report;
use App\Models\Incident;
use App\Models\ServiceLevel;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    // public function index(Incident $incident)
    // {
    //     $trueTotalIncidents = random_int(0,99);
        
    //     $incident = Incident::all();

    //     $data = [
    //         'trueTotalIncidents' => $trueTotalIncidents,
    //         'totalSLA' => $trueTotalIncidents,
    //         'totalReports' => $trueTotalIncidents,
    //         'moreThan4Days' => $trueTotalIncidents,
    //         'just4Days' => $trueTotalIncidents,
    //         'lessThan4Days' => $trueTotalIncidents,
    //         'totalIncidentsThisYear' => $trueTotalIncidents,
    //         'totalIncidentsThisMonth' => $trueTotalIncidents,
    //         'totalIncidentsToday' => $trueTotalIncidents,
    //         'totalIncidentsByMonth' => $trueTotalIncidents,
    //         'totalIncidentsByBranch' => $trueTotalIncidents,
    //         'totalIncidentsByCategory' => $trueTotalIncidents,
    //         'SeverityOutput' => $trueTotalIncidents,
    //         'IncidentsOpen' => $trueTotalIncidents,
    //         'IncidentsDone' => $trueTotalIncidents,
    //         // 'totalIncidentsByDay' => $totalIncidentsByDay,
    //     ];

    //     return $this->success('Dashboard data retrieved successfully.', $data);

    // }

    public function index()
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

// Always 24 hours (0..23)
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
    ->whereHas('sla.slaTemplate', function($q) {
        $q->where('severity_id', 3);
    })
    ->groupBy('category_id')
    ->with(['categoryDescription','sla.slaTemplate'])
    ->get();

$SeverityOutput = Incident::selectRaw('category_id, code_sla, COUNT(*) as total')
    ->groupBy('category_id','code_sla')
    ->with(['categoryDescription','sla.slaTemplate'])
    ->get();

$IncidentsOpen = Incident::where('status', '=', 1)->count();
$IncidentsDone = Incident::where('status', '=', 2)->count();


    $data = [
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

    return $this->success('Dashboard data retrieved successfully.', $data);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
