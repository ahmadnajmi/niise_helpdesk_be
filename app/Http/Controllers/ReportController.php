<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\ReportServices;
use App\Http\Traits\ResponseTrait;
use App\Http\Requests\ReportRequest;

class ReportController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $data = ReportServices::index($request);

        return $this->success('Success', $data);
    }

    public function generateReport(ReportRequest $request){
        $report_service = new ReportServices();

        $return = $report_service->generateReport($request);

        if($return['data']){
            $response =  $return['data'];

            return response($response->getBody()->getContents(), 200)->header('Content-Type', $return['contentType'])->header('Content-Disposition', 'attachment; filename="'.$return['filename'].'"');
        }
        else{
            return $this->error($return['message']);
        }
    }

}
