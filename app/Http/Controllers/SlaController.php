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
        $limit = $request->limit ? $request->limit : 25;
        
        $data =  Sla::search($request->search)->sortByField($request->sort_by, $request->sort_order)->paginate($limit);

        return new SlaCollection($data);
    }

    public function store(SlaRequest $request)
    {
        try {
            $data = $request->all();

            $data = SlaServices::create($data);
           
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

            $data = SlaServices::update($sla,$data);

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
