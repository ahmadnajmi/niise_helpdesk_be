<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Collection\BranchCollection;
use App\Http\Resources\BranchResources;
use App\Models\Branch;

class BranchController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $limit = $request->limit ? $request->limit : 15;

        $data = Branch::select('id as branch_code','state_id','name','category','location')->get()->groupBy(function($item) {
                            return $item->state_id ? $item->stateDescription->name_en : 'Unknown State';
                        });

        return $this->success('Success', $data);
        // return new BranchCollection($data);
    }

    public function show(Branch $Branch){
        $data = new BranchResources($Branch);

        return $this->success('Success', $data);
    }

    public function branchOperatingTime(Branch $Branch){
        $data = new BranchResources($Branch);

        return $this->success('Success', $data);
    }

}
