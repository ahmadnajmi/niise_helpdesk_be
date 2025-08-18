<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\SlaTemplate;
use App\Models\SlaVersion;
use App\Models\Category;
use App\Http\Resources\SlaTemplateResources;

class SlaTemplateServices
{
    public static function create($data){

        $data['code'] = self::generateCode();

        $create = SlaTemplate::create($data);

        self::generateVersion($create);
        
        $return = new SlaTemplateResources($create);

        return $return;
    }

    public static function update(SlaTemplate $sla_template,$data){

        $update = $sla_template->update($data);

        self::generateVersion($sla_template);


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

    public static function generateVersion($data){

        $get_version = SlaVersion::where('sla_template_id',$data->id)->orderBy('version','desc')->first();
        
        $data_version['version'] = $get_version ? $get_version->version + 1 : 1;
        $data_version['sla_template_id'] = $data->id;
        $data_version['response_time'] = $data->response_time;
        $data_version['response_time_type'] = $data->response_time_type;
        $data_version['response_time_penalty'] = $data->response_time_penalty;
        $data_version['resolution_time'] = $data->resolution_time;
        $data_version['resolution_time_type'] = $data->resolution_time_type;
        $data_version['resolution_time_penalty'] = $data->resolution_time_penalty;

        $create = SlaVersion::create($data_version);
    }

    
}