<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchImport implements ToModel
{
    public function model(array $row)
    {
        return new Branch([
            'name'     => $row[1],
            'state'    => $row[0],
            'location' => $row[2],
            'category' => $row[3],

        ]);
    }
}
