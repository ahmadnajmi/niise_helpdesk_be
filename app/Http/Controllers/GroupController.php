<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\GroupCollection;
use App\Http\Resources\GroupResources;
use App\Http\Requests\GroupRequest;
use App\Http\Services\GroupServices;

class GroupController extends Controller
{
    use ResponseTrait;
// DASDADA
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Group::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new GroupCollection($data);
    }

    public function store(GroupRequest $request){
        $data = $request->all();

        $data = GroupServices::create($data);
           
        return $data; 
    }

    public function show(Group $group_management)
    {
        $data = new GroupResources($group_management);

        return $this->success('Success', $data);
    }

    public function update(GroupRequest $request, Group $group_management)
    {
        $data = $request->all();

        $data = GroupServices::update($group_management,$data);
           
        return $data; 
    }

    public function destroy(Group $group_management)
    {
        $data = GroupServices::delete($group_management);
           
        return $data; 
    }

}
