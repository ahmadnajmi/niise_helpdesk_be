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

    $moreThan4Days = Incident::where('incident_date', '<', now()->subDays(4))->count();
    $just4Days = Incident::whereDate('incident_date', now()->subDays(4))->count();
    $lessThan4Days = Incident::where('incident_date', '>=', now()->subDays(3))->count();

    $totalIncidentsThisYear = Incident::whereYear('incident_date', now()->year)->count();
    // $incidentsByMonth = Incident::select(
    //         DB::raw('MONTH(incident_date) as month'),
    //         DB::raw('COUNT(*) as total')
    //     )
    //     ->whereYear('incident_date', now()->year)
    //     ->groupBy(DB::raw('MONTH(incident_date)'))
    //     ->pluck('total', 'month')
    //     ->toArray();

    // $allMonths = array_fill(1, 12, 0);
    // $incidentsByMonth = array_replace($allMonths, $incidentsByMonth);


    $totalIncidentsThisMonth = Incident::whereYear('incident_date', now()->year)
        ->whereMonth('incident_date', now()->month)
        ->count();
    $totalIncidentsToday = Incident::whereDate('incident_date', today())->count();

    // $totalIncidentsByMonth = Incident::selectRaw('MONTH(incident_date) as month, COUNT(*) as total')
    //     ->whereYear('incident_date', now()->year)
    //     ->groupBy('month')
    //     ->pluck('total','month');

    $totalIncidentsByBranch = Incident::selectRaw('branch_id, code_sla, COUNT(*) as total')
        ->groupBy('branch_id','code_sla')
        ->with(['branch','sla.slaTemplate'])
        // ->with('sla')
        ->get();

    $totalIncidentsByCategory = Incident::selectRaw('category_id, code_sla, COUNT(*) as total')
        ->groupBy('category_id' ,'code_sla')
        ->with(['categoryDescription' ,'sla.slaTemplate'])
        ->get();

    $IncidentsOpen = Incident::where('status', '=', 1)->count();

    $IncidentsDone = Incident::where('status', '=', 2)->count();

    // $SeverityOutput =

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
        // 'ArrIncidentsThisYear' => $incidentsByMonth,
        // 'totalIncidentsByMonth' => $totalIncidentsByMonth,
        'totalIncidentsByBranch' => $totalIncidentsByBranch,
        'totalIncidentsByCategory' => $totalIncidentsByCategory,
        // 'SeverityOutput' => $SeverityOutput,
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
