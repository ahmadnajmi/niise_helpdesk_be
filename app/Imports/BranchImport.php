<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\RefTable;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchImport implements ToModel
{
    public function model(array $row)
    {
        $category = $row[2] == 2 ? 'PTJ' : 'Cawangan';
        
        return new Branch([
            'branch_code' => $row[0],
            'name'     => $row[1],
            'state_id'    => $row[3],
            'category' => $category,

        ]);
    }
}
