<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\RefTable;
use App\Models\Company;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        /** 
         * $row 0 = name
         * $row 1 = nickname
         * $row 2 = ic
         * $row 3 = email
         * $row 4 = phone_no
         * $row 5 = branch
         * $row 6 = role
         * $row 7 = company
        **/ 
        $role = isset($row[6]) ? $row[6] : null;
        
        if($row[6] != 'SUPER_ADMIN'){

            if(isset($row[5])){
                $get_branch = Branch::where('name', $row[5])->first();

                $data['branch_id'] = $get_branch?->id;
                $data['state_id'] = $get_branch?->state_id;
            }
            // else{
            //     $state = RefTable::inRandomOrder()->where('code_category','state')->first();
            //     $get_branch = Branch::where('state_id', $state?->ref_code)->inRandomOrder()->first();

            //     $data['branch_id'] = $get_branch ? $get_branch->id : null;
            //     $data['state_id'] = $state?->ref_code;
            // }
           
        }    

        $data['name']  = $row[0];
        $data['nickname']  = isset($row[1]) ? $row[1] : null;
        $data['password']  = Hash::make('P@ssw0rd');
        $data['email'] =   $row[3];
        $data['phone_no'] =   $row[4];

        if(isset($row[7])){
            $get_company = Company::where('nickname',$row[7])->first();

            $data['company_id'] = $get_company?->id;
        }
      
        if(isset($row[2])){
            $data['ic_no'] =   $row[2];
        }
        // else{
        //     $data['ic_no'] =   $this->generateDummyIC();
        // }
        $create = User::create($data);

        if($role){
            $role = Role::where('role',$role)->first();
            $role = $role?->id;
        }
        else{
            $role = Role::inRandomOrder()->first();
            $role =  $role->id;
        }
        // dd($role,$row);
        $data_userrole['user_id'] = $create->id;
        $data_userrole['role_id'] = $role;

        $create = UserRole::create($data_userrole);
      
    }

    private function generateDummyIC()
    {
        $date = now()->subYears(rand(20, 50))->format('ymd');

        $state = str_pad(rand(1, 21), 2, '0', STR_PAD_LEFT);

        $last4 = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $date.$state.$last4;
    }
}
