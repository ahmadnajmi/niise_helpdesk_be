<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\MiniReportServices;

class MiniReportController extends Controller
{
    public function generate(Request $request) {
        return MiniReportServices::export($request);
    }
}
