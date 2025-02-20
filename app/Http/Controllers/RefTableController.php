<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\RefTableCollection;
use App\Http\Requests\RefTableRequest;
use App\Models\RefTable;

class RefTableController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data =  RefTableCollection::collection(RefTable::paginate(15));

        return $this->success('Success', $data);
    }

    public function store(RefTableRequest $request)
    {
        try {
            $data = $request->all();

            $create = RefTable::create($data);
           
            $data = new RefTableCollection($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(RefTable $ref_table)
    {
        $data = new RefTableCollection($ref_table);

        return $this->success('Success', $data);
    }

    public function update(RefTableRequest $request, RefTable $ref_table)
    {
        try {
            $data = $request->all();

            $update = $ref_table->update($data);

            $data = new RefTableCollection($ref_table);

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
