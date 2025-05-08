<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Sla;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\SlaCollection;
use App\Http\Resources\SlaResources;
use App\Http\Requests\SlaRequest;
use App\Http\Services\SlaServices;

class SlaController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Sla::paginate($limit);

        return new SlaCollection($data);
    }

    public function store(SlaRequest $request)
    {
        try {
            $data = $request->all();

            $create = SlaServices::create($data);
           
            $data = new SlaResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Sla $sla)
    {
        $data = new SlaResources($sla);

        return $this->success('Success', $data);
    }

    public function update(SlaRequest $request, Sla $sla)
    {
        try {
            $data = $request->all();

            $update = SlaServices::update($sla,$data);

            $data = new SlaResources($sla);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Sla $sla)
    {
        SlaServices::delete($sla);

        return $this->success('Success', null);
    }
}
