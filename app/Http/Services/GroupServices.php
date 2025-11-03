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

             self::addUserGroup($data,$group_management->id);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function addUserGroup($request,$group_id){

        if(isset($request['users'])){
            UserGroup::where('groups_id',$group_id)->delete();

            foreach($request['users'] as $user){

                $user_id = isset($user['id']) ? $user['id'] : null;

                if(!$user_id){
                    $user['user_type'] = User::FROM_IDM;

                    $create = User::create($user);

                    $user_id = $create->id;

                    $role  = Role::where('role',Role::CONTRACTOR)->first();

                    $user_role['user_id'] = $user_id;
                    $user_role['role_id'] = $role?->id;

                    UserRole::disableAuditing();

                    UserRole::create($user_role);
                }

                $data['user_id'] = $user_id;
                $data['groups_id'] = $group_id;

                UserGroup::create($data);

                
            }
        }
    }

    public static function delete(Group $group_management){

        $group_management->userGroup()->delete();

        $group_management->userGroupAccess()->delete();

        $group_management->delete();

         return self::success('Success', null);
    }
}

    
 