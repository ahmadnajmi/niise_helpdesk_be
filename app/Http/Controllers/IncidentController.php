<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Incident;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\IncidentCollection;
use App\Http\Resources\IncidentResources;
use App\Http\Requests\IncidentRequest;
use App\Http\Resources\IncidentInternalResources;
use App\Http\Services\IncidentServices;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $data = IncidentServices::index($request);

        return new IncidentCollection($data);
    }

    public function store(IncidentRequest $request)
    {
        $data = $request->all();

        $data = IncidentServices::create($data);

        return $data; 
    }

    public function show(Incident $incident)
    {
        $data = IncidentServices::view($incident);

        // $create = IncidentServices::checkPenalty($incident);

        return $data;
    }

    public function update(IncidentRequest $request, Incident $incident)
    {
        $data = $request->all();

        $data = IncidentServices::update($incident,$data,$request);

        return $data;
    }

    public function destroy(Incident $incident)
    {
        $data = IncidentServices::delete($incident);

        return $data;
    }
    
    public function downloadFile($filename){
        $filePath = 'incident/'.$filename; 
        $disk = config('filesystems.default');

        if (Storage::disk($disk)->exists($filePath)) { 
            return Storage::disk($disk)->download($filePath);
        }

        return $this->error('File not found');
    }

    public function downloadAssetFile($incident_no){
        $data = IncidentServices::downloadAssetFile($incident_no);

        return $data;
    }

    public function incidentInternal(Incident $incident){

        $data = new IncidentInternalResources($incident);

        return self::success('Success', $data);
    }

    public function generateEndDate(Incident $incident){
        $data = IncidentServices::generateEndDate($incident);

        return $data;
    }

    public function generatePenalty(Incident $incident){
        $data = IncidentServices::generatePenalty($incident);

        return self::success('Success', $data);
    }
}
