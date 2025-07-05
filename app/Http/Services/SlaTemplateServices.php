<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\SlaTemplate;
use App\Models\SlaCategory;
use App\Models\Category;
use App\Http\Resources\SlaTemplateResources;

class SlaTemplateServices
{
    public static function create($data){

        $data['code'] = self::generateCode();

        $create = SlaTemplate::create($data);
        
        $return = new SlaTemplateResources($create);

        return $return;
    }

    public static function update(SlaTemplate $sla_template,$data){

        $update = $sla_template->update($data);

        $return = new SlaTemplateResources($sla_template);

        return $return;
    }

    public static function delete(SlaTemplate $sla_template){
        Sla::where('sla_template_id',$sla_template->id)->delete();

        $sla_template->delete();

        return true;
    }

    public static function generateCode(){
        $sla_template = SlaTemplate::orderBy('code','desc')->first();

        if($sla_template){
            $code = $sla_template->code;

            $old_code = substr($code, -2);

            $next_number = str_pad($old_code + 1, 4, '0', STR_PAD_LEFT);
        }
        else{
            $next_number = '0001';
        }

        $new_code = 'ST'.$next_number;
        
        return $new_code;
    }

    
}