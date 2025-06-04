<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\SlaCategory;
use App\Http\Resources\SlaResources;

class SlaServices
{
    public static function create($data){

        $create = Sla::create($data);

        $data = self::slaCategory($data,$create->id);

        $return = new SlaResources($create);

        return $return;
    }

    public static function update(Sla $sla,$data){

        $update = $sla->update($data);

        $data = self::slaCategory($data,$sla->id);

        $return = new SlaResources($sla);


        return $return;
    }

    public static function delete(Sla $sla){
        SlaCategory::where('sla_id',$sla->id)->delete();

        $sla->delete();

        return true;
    }

    public static function slaCategory($data,$id){

        SlaCategory::where('sla_id',$id)->delete();
        
        if(isset($data['sla_category'])){

            foreach($data['sla_category'] as $sla_category){

                $data_category['category_id'] = $sla_category;
                $data_category['sla_id'] = $id;

                SlaCategory::create($data_category);
            }
        }
        return true;
    }

    
}