<?php

namespace App\Http\Services;


use App\Http\Traits\ResponseTrait;
use App\Http\Resources\GroupResources;
use App\Models\Group;
use App\Models\UserGroup;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class GroupServices
{
    use ResponseTrait;

    public static function create($data){
        try{
            $create = Group::create($data);

            self::addUserGroup($data,$create->id);

            $return = new GroupResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(Group $group_management,$data){
        try{

            $create = $group_management->update($data);

            $return = new GroupResources($group_management);

             self::addUserGroup($data,$group_management->id,true);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function addUserGroup($request,$group_id,$is_update = false){
        $undeleted_user = [];

        if(isset($request['users'])){
            // UserGroup::where('groups_id',$group_id)->delete();

            $data['groups_id'] = $group_id;

            $data['groups_id'] = $group_id;

            foreach($request['users'] as $user){

                if($is_update && isset($user['id'])){
                   $undeleted_user[] =  $user['id'];
                }
                else{
                    $data['user_type'] = $user['user_type'];
                    $data['ic_no'] = $user['ic_no'];
                    $data['name'] = $user['name'];
                    $data['email'] = $user['email'];
                    $data['company_id'] = $user['company_id'];

                   $create = UserGroup::create($data);

                   $undeleted_user[] = $create->id;
                }
            }
        }

        if($is_update){
            UserGroup::where('groups_id',$group_id)
                        ->when(count($undeleted_user) > 0, function ($query) use ($undeleted_user) {
                            $query->whereNotIn('id',$undeleted_user);
                        })
                        ->delete();
        }
    }

    public static function delete(Group $group_management){

        $group_management->userGroup()->delete();

        $group_management->userGroupAccess()->delete();

        $group_management->delete();

         return self::success('Success', null);
    }
}

    
 