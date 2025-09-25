<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\Report;
use App\Models\Incident;
use App\Models\ServiceLevel;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Services\DashboardServices;

class DashboardController extends Controller
{
    use ResponseTrait;
    protected $dashboardService;

    public function __construct(DashboardServices $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $branchId = $request->query('branch_id'); // from ?branch_id=5
        $data = $this->dashboardService->getDashboardData($branchId);

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
