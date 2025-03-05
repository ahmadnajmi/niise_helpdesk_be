<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\RefTableCollection;
use App\Http\Resources\RefTableResources;

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

    public function store(RefTableRequest $request)
    {
        try {
            $data = $request->all();

            $create = RefTable::create($data);
           
            $data = new RefTableResources($create);

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

            $update = $ref_table->update($data);

            $data = new RefTableResources($ref_table);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(RefTable $ref_table)
    {
        $ref_table->delete();

        return $this->success('Success', null);
    }
}
