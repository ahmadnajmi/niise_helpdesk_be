<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\SlaCategory;
use App\Models\Category;
use App\Http\Resources\SlaResources;

class SlaServices
{
    public static function create($data){

        $sla_id = [];

        foreach($data['sla_category'] as $sla_category){

            $data['category_id'] = $sla_category;
            $data['code'] = self::generateCode($sla_category);

            $create = Sla::create($data);
            
            $sla_id = $create->id;
        }

        // $data = self::slaCategory($data,$sla_id);

        $return = new SlaResources($create);

        return $return;
    }

    public static function update(Sla $sla,$data){

        $update = $sla->update($data);

        // $data = self::slaCategory($data,$sla->id);

        $return = new SlaResources($sla);


        return $return;
    }

    public static function delete(Sla $sla){
        SlaCategory::where('sla_id',$sla->id)->delete();

        $sla->delete();

        return true;
    }

    public static function slaCategory($data){
        
        foreach($data['sla_category'] as $sla_category){

            $data_category['category_id'] = $sla_category;
            $data_category['sla_id'] = $id;

            SlaCategory::create($data_category);
        }
        
        return true;
    }

    public static function generateCode($category_id){
        $get_sla = Sla::where('category_id',$category_id)->orderBy('code','desc')->first();

        $category = Category::find($category_id);

        if($get_sla){
            $code = $get_sla->code;

            $old_code = substr($code, -2);

            $next_number = str_pad($old_code + 1, 2, '0', STR_PAD_LEFT); // "06"
        }
        else{
            $next_number = '01';
        }

        $new_code = strtoupper($category->name).$next_number;
        
        return $new_code;
    }

    
}