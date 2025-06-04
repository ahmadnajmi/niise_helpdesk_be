<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Models\UserGroupAccess;
use App\Http\Resources\UserResources;

class UserServices
{
    public static function create($data){
        $data['password'] = Hash::make('P@ssw0rd');

        $create = User::create($data);

        $group_user = self::groupUser($data,$create->id);

        if($data['role']){
            $user_role['user_id'] = $create->id;
            $user_role['role_id'] = $data['role'];

            UserRole::disableAuditing();

            UserRole::create($user_role);
        }
        
        return $create;
    }

    public static function update(User $user,$data){

        $update = $user->update($data);

        $data = self::groupUser($data,$user->id);

        return $update;
    }

    public static function groupUser($data,$user_id){

        if(isset($data['group_user'])){
            foreach($data['group_user'] as $group_id){

                $data_group_user['user_id'] = $user_id;
                $data_group_user['groups_id'] = $group_id;
    
                UserGroup::create($data_group_user);
            }
        }

        if(isset($data['group_user_access'])){
            foreach($data['group_user_access'] as $access_group_id){

                $data_group_user_access['user_id'] = $user_id;
                $data_group_user_access['groups_id'] = $access_group_id;
    
                UserGroupAccess::create($data_group_user_access);
            }
        }

        return true;
    }

    public static function delete(User $user){

        UserGroup::where('user_id',$user->id)->delete();

        $user->delete();

        return true;

    }

    public static function searchIcNo($request){
        $user = User::filter()
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'Kontraktor'); 
                    })->first();
                    
        $data = $user ? new UserResources($user) : null;

        return $data;
    }

     
}