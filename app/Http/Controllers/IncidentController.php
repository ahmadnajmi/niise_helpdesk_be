<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Incident;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\IncidentCollection;
use App\Http\Resources\IncidentResources;
use App\Http\Requests\IncidentRequest;
use App\Http\Services\IncidentServices;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Incident::paginate($limit);

        return new IncidentCollection($data);
    }

    public function store(IncidentRequest $request)
    {
        try {
            $data = $request->all();

            $data = IncidentServices::create($data,$request);

            return $this->generalResponse($data);
          
        } catch (\Throwable $th) {
             return $this->error($th->getMessage());
        }
    }

    public function show(Incident $incident)
    {
        $data = new IncidentResources($incident);

        return $this->success('Success', $data);
    }

    public function update(IncidentRequest $request, Incident $incident)
    {
        try {
            $data = $request->all();

            $data = IncidentServices::update($incident,$data);

            return $this->generalResponse($data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Incident $incident)
    {
        $data = IncidentServices::delete($incident);

        return $this->success('Success', null);
    }
    
    public function downloadFile($filename){
        $filePath = 'incident/'.$filename; 

        if (Storage::disk('local')->exists($filePath)) { 
            return Storage::disk('local')->download($filePath);
        }

        return $this->error('File not found');
    }

    public function downloadAssetFile($incident_no){
        $incident =  Incident::where('incident_no',$incident_no)->first();

        $filePath = 'incident/'.$incident->asset_file; 

        if (Storage::disk('local')->exists($filePath)) { 
            return Storage::disk('local')->download($filePath);
        }

        return $this->error('File not found');

    }
}
