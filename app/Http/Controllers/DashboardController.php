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
        $trueTotalIncidents = random_int(0,99);

        $data = [
            'trueTotalIncidents' => $trueTotalIncidents,
            'totalSLA' => $trueTotalIncidents,
            'totalReports' => $trueTotalIncidents,
            'moreThan4Days' => $trueTotalIncidents,
            'just4Days' => $trueTotalIncidents,
            'lessThan4Days' => $trueTotalIncidents,
            'totalIncidentsThisYear' => $trueTotalIncidents,
            'totalIncidentsThisMonth' => $trueTotalIncidents,
            'totalIncidentsToday' => $trueTotalIncidents,
            'totalIncidentsByMonth' => $trueTotalIncidents,
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
