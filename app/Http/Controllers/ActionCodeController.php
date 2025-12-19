<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\ActionCodeCollection;
use App\Http\Resources\ActionCodeResources;
use App\Http\Requests\ActionCodeRequest;
use App\Http\Services\ActionCodeServices;
use App\Models\ActionCode;  

class ActionCodeController extends Controller
{//test
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  ActionCode::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new ActionCodeCollection($data);
    }

    public function store(ActionCodeRequest $request)
    {
        $data = $request->all();

        $create = ActionCodeServices::create($data);
        
        return $create;
    }

    public function show(ActionCode $action_code)
    {
        $data = new ActionCodeResources($action_code);

        return $this->success('Success', $data);
    }

    public function update(ActionCodeRequest $request, ActionCode $action_code)
    {
        $data = $request->all();

        $update = ActionCodeServices::update($action_code,$data);

        return $update;
    }

    public function destroy(ActionCode $action_code)
    {
        $delete = ActionCodeServices::delete($action_code);

        return $delete;
    }
}
