<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\AdhocReportCollection;
use App\Http\Resources\AdhocReportResources;
use App\Http\Requests\AdhocReportRequest;
use App\Http\Services\AdhocReportServices;
use Illuminate\Support\Facades\Storage;

class AdhocReportController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Report::orderBy('updated_at', 'desc')->paginate($limit);

        return new AdhocReportCollection($data);
    }

    public function store(AdhocReportRequest $request){
        $data = $request->all();

        $create = AdhocReportServices::create($data);
        
        return $create;
    }

    public function show(Report $adhoc_report) {
        $data = new AdhocReportResources($adhoc_report);

        return $this->success('Success', $data);
    }

    public function update(AdhocReportRequest $request, Report $adhoc_report){
        $data = $request->all();

        $update = AdhocReportServices::update($adhoc_report, $data);

        return $update;
    }

    public function destroy(Report $adhoc_report){

        if($adhoc_report->is_default == 1){
            return $this->error(__('report.message.default_report'));
        }

        $adhoc_report->delete();

        return $this->success('Success', null);
    }

    public function downloadFile($filename){
        $filePath = 'report/non_default/'.$filename; 

        $disk = config('filesystems.default');

        if (Storage::disk($disk)->exists($filePath)) { 
            return Storage::disk($disk)->download($filePath);
        }

        return $this->error('File not found for '.$filename);
    }
}
