<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\RefTableCollection;
use App\Http\Services\RefTableServices;
use App\Http\Requests\RefTableRequest;
use App\Models\RefTable;

class RefTableController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  RefTable::filter()->paginate($limit);

        return new RefTableCollection($data);
    }

    public function dropdownIndex(){
        $data = RefTable::groupBy('code_category')->pluck('code_category');
            
        $data = count($data) > 0 ? $data : ['state','action_code_category','issue_level']; 

        return $this->success('Success', $data);
    }

    public function store(RefTableRequest $request)
    {
        try {
            $data = $request->all();

            $data = RefTableServices::create($data);
           
            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(RefTable $ref_table)
    {
        $data = new RefTableResources($ref_table);

        return $this->success('Success', $data);
    }

    public function update(RefTableRequest $request, RefTable $ref_table)
    {
        try {
            $data = $request->all();

            $return = RefTableServices::update($data);

            return $this->success('Success', $return);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(RefTable $ref_table)
    {
        $ref_table->delete();

        return $this->success('Success', null);
    }

    public function dropdownValueIndex(){

        $data = RefTable::select('ref_code','code_category','name_en','name')->filter()->get();
            
        return $this->success('Success', $data);
    }
}
