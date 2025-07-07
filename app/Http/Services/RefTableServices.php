<?php

namespace App\Http\Services;

use App\Models\RefTable;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Resources\RefTableResources;

class RefTableServices
{
    public static function create($data){

        $data['ref_code'] = self::generateRefCode($data);

        $create = RefTable::create($data);
        
        $return = new RefTableResources($create);

        return $return;
    }

    public static function update(RefTable $ref_table,$data){

        $update = $ref_table->update($data);

        $return = new RefTableResources($ref_table);

        return $return;
    }

    public static function generateRefCode($data){

        $old_id = 0;
        $get_code = RefTable::select('ref_code')->where('code_category',$data['code_category'])->first();

        if($get_code){
            $old_id = $get_code->ref_code;
        }
       
        $new_id = $old_id + 1;

        return $new_id;
    }
}