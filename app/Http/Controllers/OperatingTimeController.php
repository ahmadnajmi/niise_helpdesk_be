<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\OperatingTime;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\OperatingTimeCollection;
use App\Http\Resources\OperatingTimeResources;
use App\Http\Requests\OperatingTimeRequest;

class OperatingTimeController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  OperatingTime::paginate($limit);

        return new OperatingTimeCollection($data);
    }

    public function store(OperatingTimeRequest $request)
    {
        try {
            $data = $request->all();

            $create = OperatingTime::create($data);
           
            $data = new OperatingTimeResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(OperatingTime $operating_time)
    {
        $data = new OperatingTimeResources($operating_time);

        return $this->success('Success', $data);
    }

    public function update(OperatingTimeRequest $request, OperatingTime $operating_time)
    {
        try {
            $data = $request->all();

            $update = $operating_time->update($data);

            $data = new OperatingTimeResources($operating_time);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(OperatingTime $operating_time)
    {
        $operating_time->delete();

        return $this->success('Success', null);
    }
}
