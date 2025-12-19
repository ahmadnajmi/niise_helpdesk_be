<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\OperatingTime;
use App\Models\Branch;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\OperatingTimeCollection;
use App\Http\Collection\BranchCollection;
use App\Http\Resources\BranchResources;
use App\Http\Resources\OperatingTimeResources;
use App\Http\Requests\OperatingTimeRequest;
use App\Http\Services\OperatingTimeServices;

class OperatingTimeController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Branch::filter()->search($request->search)->sortByField($request)->paginate($limit);
        
        return new BranchCollection($data);
    }

    public function store(OperatingTimeRequest $request){
       
        $data = $request->all();

        $data = OperatingTimeServices::create($data);
         
        return $data; 
    }

    public function show(OperatingTime $operating_time){
        $data = new OperatingTimeResources($operating_time);

        return $this->success('Success', $data);
    }

    public function update(OperatingTimeRequest $request, OperatingTime $operating_time){  
        $data = $request->all();

        $data = OperatingTimeServices::update($operating_time,$data);

        return $data;
    }

    public function destroy(OperatingTime $operating_time){
        $operating_time->delete();

        return $this->success('Success', null);
    }

    public function operantingTimeBranch(Request $request,$branch_id){
        $limit = $request->limit ? $request->limit : 15;

        $operating_time = OperatingTime::where('branch_id',$branch_id)->paginate($limit);

        return new OperatingTimeCollection($operating_time);
    }

    public function operantingTimeBranchDelete($branch_id){
        OperatingTime::where('branch_id',$branch_id)->delete();

        return $this->success('Success', null);
    }
}
