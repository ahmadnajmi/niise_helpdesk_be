<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\IncidentResolution;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\IncidentResolutionCollection;
use App\Http\Resources\IncidentResolutionResources;
use App\Http\Requests\IncidentResolutionRequest;
use App\Http\Services\IncidentResolutionServices;

class IncidentResolutionController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  IncidentResolution::paginate($limit);

        return new IncidentResolutionCollection($data);
    }

    public function store(IncidentResolutionRequest $request)
    {
        $data = $request->all();
        
        $data = IncidentResolutionServices::create($data);

        return $data;     
    }

    public function show(IncidentResolution $incident_solution)
    {
        $data = new IncidentResolutionResources($incident_solution);

        return $this->success('Success', $data);
    }

    public function update(IncidentResolutionRequest $request, IncidentResolution $incident_solution)
    {
       
        $data = $request->all();

        $data = IncidentResolutionServices::update($incident_solution,$data);

        return $data;
    }

    public function destroy(IncidentResolution $incident_solution)
    {
        $incident_solution->delete();

        return $this->success('Success', null);
    }
}
