<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\ActionCodeCollection;
use App\Http\Resources\ActionCodeResources;
use App\Http\Requests\ActionCodeRequest;
use App\Models\ActionCode;

class ActionCodeController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  ActionCode::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new ActionCodeCollection($data);
    }

    public function store(ActionCodeRequest $request)
    {
        try {
            $data = $request->all();

            $create = ActionCode::create($data);
           
            $data = new ActionCodeResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(ActionCode $action_code)
    {
        $data = new ActionCodeResources($action_code);

        return $this->success('Success', $data);
    }

    public function update(ActionCodeRequest $request, ActionCode $action_code)
    {
        try {
            $data = $request->all();

            $update = $action_code->update($data);

            $data = new ActionCodeResources($action_code);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(ActionCode $action_code)
    {
        $action_code->delete();

        return $this->success('Success', null);
    }
}
