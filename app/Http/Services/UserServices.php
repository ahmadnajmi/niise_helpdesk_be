<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Models\UserGroupAccess;
use App\Models\RefTable;
use App\Models\Branch;
use App\Models\Role;
use App\Http\Resources\UserResources;
use Faker\Factory as Faker;

class UserServices
{
    use ResponseTrait;

    public static function create($data){

        try {
            $check_user = User::where('ic_no',$data['ic_no'])->exists();

            if(!$check_user){
                $clean_name = strtoupper(str_replace(' ', '', $data['name']));  
                $first    = substr($clean_name, 0, 6);
                $last = substr($data['ic_no'], -6);

                $data['password'] = Hash::make($first.$last);

                if($data['role'] == Role::BTMR || $data['role'] == Role::JIM){

                    $data['user_type'] = User::FROM_IDM;
                }
                else{
                    $data['user_type'] = User::FROM_HDS;
                }

                if(isset($data['category_id'])){
                    $data['category_id'] = json_encode($data['category_id']);
                }

                $create = User::create($data);

                $group_user = self::groupUser($data,$create);

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

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
        
        return $return;
    }

    public static function update(User $user,$data){
        try {
            $is_disabled = isset($data['is_disabled']) ? $data['is_disabled'] : $user->is_disabled;

            if($is_disabled == false){
                $data['is_disabled'] = false;
                $data['failed_attempts'] = 0;
            }
            
            if(isset($data['category_id'])){
                $data['category_id'] = json_encode($data['category_id']);
            }

            $update = $user->update($data);

            $data = self::groupUser($data,$user);

            $return = new UserResources($user);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function groupUser($data,$user){

        if(isset($data['group_user'])){
            UserGroup::where('ic_no',$data['ic_no'])->delete();

            foreach($data['group_user'] as $group_id){

                $data_group_user['groups_id'] = $group_id;
                $data_group_user['user_type'] = RefTable::USER_TYPE_USER;
                $data_group_user['ic_no'] = $user->ic_no;
                $data_group_user['name'] = $user->name;
                $data_group_user['email'] = $user->email;
                $data_group_user['company_id'] = $user->company_id;
    
                UserGroup::create($data_group_user);
            }
        }

        if(isset($data['group_user_access'])){
            UserGroupAccess::where('user_id',$user->id)->delete();

            foreach($data['group_user_access'] as $access_group_id){

                $data_group_user_access['user_id'] = $user->id;
                $data_group_user_access['groups_id'] = $access_group_id;
    
                UserGroupAccess::create($data_group_user_access);
            }
        }

        return true;
    }

    public static function delete(User $user){

        UserGroup::where('user_id',$user->id)->delete();
        UserGroupAccess::where('user_id',$user->id)->delete();

        $user->delete();

        return true;
    }

    public static function searchIcNo($request){
        $user = User::with('company')->hideSuperAdmin()->filter()->first();
        
        if($user){
            $return  = [
                'message' => __('user.message.user_found'),
                'data' => $user,
            ];  
        }
        else{
            $return = self::checkSystemIDM($request);
        }

        return $return;
    }

    public static function checkSystemIDM($request,$hint = 'contractor'){ 
        $faker = Faker::create(config('app.faker_locale'));

        $dummy_users = [
            '900101141234' => 'Ahmad Najmi Bin Hassan',
            '880202052345' => 'Tan Wei Jie',
            '920303013456' => 'Siti Nur Aisyah Binti Rahman',
            '850404104567' => 'Rajesh Kumar',
            '930505085678' => 'Lim Mei Ling',
            '810606036789' => 'Mohd Farhan Bin Zulkifli',
            '870707127890' => 'Ng Kok Leong',
            '950808068901' => 'Nurul Huda Binti Ahmad',
            '960909029012' => 'Arun Prakash',
            '970101090123' => 'Lee Jia Wen',
            '890202071235' => 'Azlan Bin Ismail',
            '910303132346' => 'Chong Wai Mun',
            '820404043457' => 'Farah Nadia Binti Salleh',
            '840505114568' => 'Vijay Anand',
            '860606155679' => 'Wong Siew Mei',
            '980707176780' => 'Hafiz Bin Abdullah',
            '991212167891' => 'Tan Li Xian',
            '930909108902' => 'Sharmila Devi',
            '900808019013' => 'Muhammad Iqbal Bin Roslan',
            '920101050124' => 'Chan Yee Hong',
            '881010141236' => 'Aminah Binti Osman',
            '910911022347' => 'Teo Kok Seng',
            '970707033458' => 'Priya Kumari',
            '850505074569' => 'Khairul Anuar Bin Ramli',
            '860909135670' => 'Goh Hui Min',
            '940101086781' => 'Amirul Hakim Bin Yusof',
            '980202097892' => 'Liew Jian Hao',
            '951010128903' => 'Kavitha Rani',
            '990101069014' => 'Mohd Danish Bin Zakaria',
            '930505040125' => 'Lim Kok Wei',
            '901212101237' => 'Azizah Binti Halim',
            '920404152348' => 'Ong Wei Han',
            '960303053459' => 'Suresh Kumar',
            '870707064560' => 'Nur Sabrina Binti Latif',
            '880808115671' => 'Tan Chee Seng',
            '891010036782' => 'Haziq Bin Rahman',
            '950505097893' => 'Lee Hui Yee',
            '981212018904' => 'Balakrishnan',
            '960707079015' => 'Amirah Binti Saiful',
            '911212080126' => 'Chia Kok Soon',
            '890101131238' => 'Rashid Bin Hassan',
            '900202022349' => 'Ng Wei Xian',
            '940303063450' => 'Anita Kumari',
            '870404144561' => 'Shahrul Nizam Bin Fauzi',
            '920505125672' => 'Koh Jia Hao',
            '980606046783' => 'Farid Bin Salleh',
            '991010107894' => 'Tan Hui Min',
            '951212078905' => 'Manogaran',
            '970202019016' => 'Zulkifli Bin Hamid',
            '850101050127' => 'Lau Zhen Wei'
        ];
            
        if (array_key_exists($request->ic_no, $dummy_users)) {
            $gender = $faker->randomElement(['male', 'female']);
            $position = $faker->randomElement(['Pengarah Imigresen Negeri', 'Ketua Pejabat','Pegawai Imigresen (PI)','Timb. Pen Pengarah Imigresen (TPPI)']);

            $branch = Branch::inRandomOrder()->first();

            $data['ic_no'] = $request->ic_no;
            $data['name'] = $dummy_users[$request->ic_no];
            $data['nickname'] = $faker->userName;
            $data['position'] = $position;
            $data['branch_id'] = $branch->id;
            $data['email'] = $faker->safeEmail;
            $data['phone_no'] = $faker->phoneNumber;
            $data['address'] = $faker->address;
            $data['postcode'] = $faker->postcode;
            $data['city'] = $faker->city;
            $data['state_id'] = $branch->state_id;
            $data['stateDescription'] = RefTable::where('code_category','state')->where('ref_code', $branch->state_id)->first();

            $message =  __('user.message.user_exists_adm');
        }
        else{
            $message = $hint == 'contractor' ?  __('user.message.user_contractor_exists') :  __('user.message.user_not_exists');

            $data = null;
        }

        $return  = [
            'message' => $message,
            'data' => $data,
        ];  

        return $return;
    }
    
    public static function generateMalayName(){
        $firstNames = [
            'Ahmad', 'Ali', 'Azman', 'Faizal', 'Hafiz', 'Imran', 'Khairul', 'Najmi', 'Shafiq', 'Zul',
            'Ismail', 'Syazwan', 'Ridzuan', 'Fikri', 'Nizam', 'Firdaus', 'Zaki', 'Hasbullah', 'Roslan', 'Farid',
            'Siti', 'Aisyah', 'Nur', 'Fatimah', 'Zulaikha', 'Aina', 'Farah', 'Balqis', 'Syafiqah', 'Nadiah',
            'Raihan', 'Liyana', 'Maisarah', 'Sabrina', 'Marissa', 'Hanis', 'Diyana', 'Amirah', 'Anis', 'Azura'
        ];

        $lastNames = [
            'bin Ahmad', 'bin Ismail', 'bin Hassan', 'bin Omar', 'bin Abdullah', 'bin Rahman', 'bin Yusof', 'bin Ibrahim',
            'bin Saad', 'bin Karim', 'bin Rosli', 'bin Mahmud', 'bin Latif', 'bin Salleh', 'bin Idris',
            'binti Ahmad', 'binti Ismail', 'binti Hassan', 'binti Omar', 'binti Abdullah', 'binti Rahman', 'binti Yusof', 'binti Ibrahim',
            'binti Saad', 'binti Karim', 'binti Rosli', 'binti Mahmud', 'binti Latif', 'binti Salleh', 'binti Idris'
        ];

        return  $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    public static function searchIcNoContractor($request){
        $user = User::hideSuperAdmin()->filter()->first();

        if($user){
            $return  = [
                'message' => __('user.message.user_exists'),
                'data' => $user,
            ]; 
        }
        else{
            $return = self::checkSystemIDM($request,'contractor');
        }

        return $return;
    }
     
}