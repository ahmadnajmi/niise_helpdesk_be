<?php

namespace App\Imports;

use App\Models\RefTable;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class RefTableImport implements ToModel
{
    public function model(array $row)
    {        
        $data['code_category']  = $row[0];
        $data['ref_code']  = $row[1];
        $data['name_en']    = $row[2];
        $data['name'] = $row[2];

        $create = RefTable::create($data);
      
    }
}
