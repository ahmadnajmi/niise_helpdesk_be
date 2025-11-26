<?php

namespace App\Imports;

use App\Models\Module;
use Maatwebsite\Excel\Concerns\ToModel;

class ModuleImport implements ToModel
{
    public function model(array $row)
    {
        if(isset($row[1])){
            $parent_module = Module::where('code',$row[1])->first();

            $data['module_id'] = $parent_module?->id;
        }
        $data['code'] = $row[0];
        $data['name'] = $row[2];
        $data['name_en'] = $row[3];
        $data['description'] = $row[4];
        $data['svg_path'] =  isset($row[5]) ? $row[5] : null;
        $data['order_by'] =  isset($row[6]) ? $row[6] : null;
      
        $create = Module::create($data);

        return $create;
    }
}
