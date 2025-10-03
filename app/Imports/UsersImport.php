<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        $get_branch = Branch::inRandomOrder()->first();
        
        $data['name']  = $row[0];
        $data['nickname']  = $row[0];
        $data['password']  = Hash::make('P@ssw0rd');
        $data['position']    = $row[1];
        $data['branch_id'] = $get_branch ? $get_branch->id : null;
        $data['email'] =   $row[3];
        $data['phone_no'] =   $row[4];
        $data['category_office'] =   $row[5];
        $data['ic_no'] =   $this->generateDummyIC();

        $create = User::create($data);

        if(isset($row[6])){
            $role = $row[6];
        }
        else{
            $role = Role::inRandomOrder()->first();
            $role =  $role->id;
        }

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
