<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Models\UserGroupAccess;
use App\Models\RefTable;
use App\Http\Resources\UserResources;
use Faker\Factory as Faker;

class UserServices
{
    public static function create($data){

        $check_user = User::where('ic_no',$data['ic_no'])->exists();

        if(!$check_user){
            $data['password'] = Hash::make('P@ssw0rd');

            $create = User::create($data);

            $group_user = self::groupUser($data,$create->id);

            if($data['role']){
                $user_role['user_id'] = $create->id;
                $user_role['role_id'] = $data['role'];

                UserRole::disableAuditing();

                UserRole::create($user_role);
            }

            $return = new UserResources($create);
        }
        else{
            $return = null;
        }
        

        return $return;
    }

    public static function update(User $user,$data){

        $update = $user->update($data);

        $data = self::groupUser($data,$user->id);

        $return = new UserResources($user);

        return $return;
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
        $user = User::filter()->first();
        
        if($user){
            $return  = [
                'message' => 'User already exists in system Helpdesk',
                'data' => null,
            ]; 
        }
        else{
            $return = self::checkSystemIDM($request);
        }

        return $return;
    }

    public static function checkSystemIDM($request){ 
        $faker = Faker::create('ms_My');

        $dummy_icno = [
            '900101141234', '880202052345', '920303013456', '850404104567', '930505085678',
            '810606036789', '870707127890', '950808068901', '960909029012', '970101090123',
            '890202071235', '910303132346', '820404043457', '840505114568', '860606155679',
            '980707176780', '991212167891', '930909108902', '900808019013', '920101050124',
            '881010141236', '910911022347', '970707033458', '850505074569', '860909135670',
            '940101086781', '980202097892', '951010128903', '990101069014', '930505040125',
            '901212101237', '920404152348', '960303053459', '870707064560', '880808115671',
            '891010036782', '950505097893', '981212018904', '960707079015', '911212080126',
            '890101131238', '900202022349', '940303063450', '870404144561', '920505125672',
            '980606046783', '991010107894', '951212078905', '970202019016', '850101050127'
        ];
            
        if(in_array($request->ic_no, $dummy_icno)){

            $gender = $faker->randomElement(['male', 'female']);
            $position = $faker->randomElement(['Pengarah Imigresen Negeri', 'Ketua Pejabat','Pegawai Imigresen (PI)','Timb. Pen Pengarah Imigresen (TPPI) ']);

            $data['ic_no'] = $request->ic_no;
            $data['name'] = $faker->name($gender);
            $data['nickname'] = $faker->userName;
            $data['position'] = $position;
            $data['branch'] = $faker->numberBetween(1,59);
            $data['email'] = $faker->safeEmail;
            $data['phone_no'] = $faker->phoneNumber;
            $data['address'] = $faker->address;
            $data['postcode'] = $faker->postcode;
            $data['city'] = $faker->city;
            $data['state_id'] = $faker->numberBetween(1,16);
            $data['stateDescription'] = RefTable::where('code_category','state')->where('ref_code',$data['state_id'])->first();

            $message = 'Ic Number found in IDM & ADM.You may Proceed';
        }
        else{
            $message = 'Ic Number not found in IDM & ADM.You cannot Proceed';

            $data = null;
        }

        $return  = [
            'message' => $message,
            'data' => $data,
        ];  

        return $return;
    }

     
}