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
        $data['name'] = $row[3];

        if(isset($row[4]) && isset($row[5])){
            $get_ref_table = RefTable::where('code_category',$row[4])->where('name_en',$row[5])->first();

            $data['ref_code_parent'] = $get_ref_table?->ref_code;
        }

        $create = RefTable::create($data);
      
    }
}
