<?php

namespace App\Imports;

use App\Models\Role;
use Maatwebsite\Excel\Concerns\ToModel;

class RoleImport implements ToModel
{
    public function model(array $row)
    {
        $data['role'] = $row[0];
        $data['name'] = $row[1];
        $data['name_en'] = $row[2];
        $data['description'] = $row[3];

        $create = Role::create($data);

        return $create;
    }
}
