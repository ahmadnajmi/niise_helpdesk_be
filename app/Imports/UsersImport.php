<?php

namespace App\Imports;

use App\Models\IdentityManagement\User;
use App\Models\IdentityManagement\Branch;

use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        // $get_branch = Branch::select('id')->where('name',$row[2])->first();

        $data['name']  = $row[0];
        $data['position']    = $row[1];
        $data['branch_id'] = $row[2];
        $data['email'] =   $row[3];
        $data['phone_no'] =   $row[4];
        $data['category_office'] =   $row[5];

        $create = User::create($data);
      
    }
}
