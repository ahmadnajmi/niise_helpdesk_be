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

        $branch = Branch::where('id', $row[0])->first();

        $data_branch['name'] = $row[1];
        $data_branch['state_id'] = $row[3];
        $data_branch['category'] = $category;

        if($branch){
            $branch->update($data_branch);
        }
        else{
            $data_branch['id'] = $row[0];
            
            $branch =  Branch::create($data_branch);
        }
        return $branch;

    }
}
