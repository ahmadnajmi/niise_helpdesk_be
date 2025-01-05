<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Incident;
use App\Models\Report;
use App\Models\ServiceLevel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Totals
        $trueTotalIncidents = Incident::count();
        $totalSLA = ServiceLevel::count();
        $totalReports = Report::count();

        // Idle Incidents
        $moreThan4Days = Incident::where('cm_close_datetm', null)->where('cm_start_date', '<', now()->subDays(4))->count();
        $just4Days = Incident::where('cm_close_datetm', null)->where('cm_start_date', '<=', now()->subDays(4))->where('cm_start_date', '>=', now()->subDays(5))->count();
        $lessThan4Days = Incident::where('cm_close_datetm', null)->where('cm_start_date', '>', now()->subDays(5))->count();

        // Total Incidents This Year
        $totalIncidentsThisYear = Incident::whereYear('cm_start_date', now()->year)->count();

        // Total Incidents This Month
        $totalIncidentsThisMonth = Incident::whereYear('cm_start_date', now()->year)->whereMonth('cm_start_date', now()->month)->count();

        // Total Incidents Today
        $totalIncidentsToday = Incident::whereDate('cm_start_date', now()->toDateString())->count();

        // Total Incidents By Month
        $incidentData = Incident::selectRaw('MONTH(cm_start_date) as month, COUNT(*) as total')
            ->whereYear('cm_start_date', now()->year)
            ->groupByRaw('MONTH(cm_start_date)')
            ->orderByRaw('MONTH(cm_start_date)')
            ->get()
            ->keyBy('month'); // Key by 'month' for easier lookup

        $totalIncidentsByMonth = collect(range(1, 12))->map(function ($month) use ($incidentData) {
            return [
                'month' => $month,
                'total' => $incidentData->get($month)->total ?? 0, // Use 0 if no data for the month
            ];
        })->toArray();


        // Total Incidents By Day
        // $totalIncidentsByDay = Incident::selectRaw('DAY(cm_start_date) as day, COUNT(*) as total')
        //     ->whereYear('cm_start_date', now()->year)
        //     ->whereMonth('cm_start_date', now()->month)
        //     ->groupBy('day')
        //     ->get();

        $incidentDataByDay = Incident::selectRaw('DAY(cm_start_date) as day, COUNT(*) as total')
            ->whereYear('cm_start_date', now()->year)
            ->whereMonth('cm_start_date', now()->month)
            ->groupByRaw('DAY(cm_start_date)')
            ->orderByRaw('DAY(cm_start_date)')
            ->get()
            ->keyBy('day');

        $totalIncidentsByDay = collect(range(1, 31))->map(function ($day) use ($incidentDataByDay) {
            return [
                'day' => $day,
                'total' => $incidentDataByDay->get($day)->total ?? 0, // Use 0 if no data for the month
            ];
        })->toArray();

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
            'totalIncidentsByMonth' => $totalIncidentsByMonth,
            // 'totalIncidentsByDay' => $totalIncidentsByDay,
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
