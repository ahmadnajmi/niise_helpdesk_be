<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\IncidentSolution;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\IncidentSolutionCollection;
use App\Http\Resources\IncidentSolutionResources;
use App\Http\Requests\IncidentSolutionRequest;
use App\Http\Services\IncidentSolutionServices;

class IncidentSolutionController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  IncidentSolution::paginate($limit);

        return new IncidentSolutionCollection($data);
    }

    public function store(IncidentSolutionRequest $request)
    {
        try {
            $data = $request->all();
           
            $data = IncidentSolutionServices::create($data);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(IncidentSolution $incident_solution)
    {
        $data = new IncidentSolutionResources($incident_solution);

        return $this->success('Success', $data);
    }

    public function update(IncidentSolutionRequest $request, IncidentSolution $incident_solution)
    {
        try {
            $data = $request->all();

            $data = IncidentSolutionServices::update($incident_solution,$data);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(IncidentSolution $incident_solution)
    {
        $incident_solution->delete();

        return $this->success('Success', null);
    }
}
