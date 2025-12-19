<?php

namespace App\Http\Services;

use App\Models\RefTable;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Resources\RefTableResources;
use App\Http\Traits\ResponseTrait;

class RefTableServices
{
    use ResponseTrait;

    public static function create($data){

        try{
            $data['ref_code'] = self::generateRefCode($data);

            $create = RefTable::create($data);
            
            $return = new RefTableResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(RefTable $ref_table,$data){

        try{
            $update = $ref_table->update($data);

            $return = new RefTableResources($ref_table);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function generateRefCode($data){

        $old_id = 0;
        $get_code = RefTable::select('ref_code')->where('code_category',$data['code_category'])->orderBy('ref_code','desc')->first();

        if($get_code){
            $old_id = $get_code->ref_code;
        }
       
        $new_id = $old_id + 1;

        return $new_id;
    }
}