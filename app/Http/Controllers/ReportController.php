<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ReportServices;
use App\Http\Traits\ResponseTrait;

class ReportController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $data = ReportServices::index($request);

        return $this->success('Success', $data);
    }

}
