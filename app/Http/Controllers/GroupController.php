<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\GroupCollection;
use App\Http\Resources\GroupResources;
use App\Http\Requests\GroupRequest;

class GroupController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Group::paginate($limit);

        return new GroupCollection($data);
    }

    public function store(GroupRequest $request)
    {
        try {
            $data = $request->all();

            $create = Group::create($data);

            $this->addUser($data,$create->id);
           
            $data = new GroupResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Group $group_management)
    {
        $data = new GroupResources($group_management);

        return $this->success('Success', $data);
    }

    public function update(GroupRequest $request, Group $group_management)
    {
        try {
            $data = $request->all();

            $update = $group_management->update($data);

            $new_data = new GroupResources($group_management);

            $this->addUser($data,$group_management->id);

            return $this->success('Success', $new_data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Group $group_management)
    {
        $group_management->userGroup()->delete();

        $group_management->userGroupAccess()->delete();

        $group_management->delete();


        return $this->success('Success', null);
    }


    public function addUser($request,$group_id){

        if(isset($request['users'])){
            UserGroup::where('groups_id',$group_id)->delete();

            foreach($request['users'] as $user_id){

                $data['user_id'] = $user_id;
                $data['groups_id'] = $group_id;

                UserGroup::create($data);
            }
        }
    }
}
