<?php

namespace App\Imports;

use App\Models\IdentityManagement\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'name'     => $row[0],
            'position'    => $row[1],
            'location' => $row[2],
            'email' =>   $row[3],
            'phone_no' =>   $row[4],
            'category_office' =>   $row[5],
        ]);
    }
}
