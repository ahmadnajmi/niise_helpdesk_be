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
use App\Http\Collection\BaseResource;

class DashboardController extends Controller
{
   
    public function index(Request $request)
    {
        $data = DashboardServices::index($request);

        return new BaseResource($data, 200, 'Success',true);
    }

    public function dashboardGraph(Request $request){
        $data = DashboardServices::getDashboardGraph($request);
    
        return $data;

    }
}
