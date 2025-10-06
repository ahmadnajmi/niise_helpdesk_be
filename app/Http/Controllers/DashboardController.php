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
   
    public function index(Request $request)
    {
        // $data = DashboardServices::index($request);
        $data =  DashboardServices::getDashboardData();
           
        return $data;  
    }
}
