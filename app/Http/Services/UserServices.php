<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGroup;

class UserServices
{
    public static function create($data){

        $data['password'] = Hash::make('P@ssw0rd');

        $create = User::create($data);

        $data = self::groupUser($data,$create->id);

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

        return true;
    }

    public static function delete(User $user){

        UserGroup::where('user_id',$user->id)->delete();

        $user->delete();

        return true;

    }
}