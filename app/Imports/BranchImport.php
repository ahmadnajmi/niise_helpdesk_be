<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\RefTable;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchImport implements ToModel
{
    public function model(array $row)
    {
        $get_state = RefTable::where('code_category','state')->where('name_en',$row[0])->first();
        
        return new Branch([
            'name'     => $row[1],
            'state_id'    => $get_state?->ref_code,
            'location' => $row[2],
            'category' => $row[3],

        ]);
    }
}
