<?php

namespace App\Http\Services;
use App\Models\Sla;
use App\Models\SlaTemplate;
use App\Models\SlaVersion;
use App\Models\Category;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Traits\ResponseTrait;

class SlaTemplateServices
{
    use ResponseTrait;

    public static function create($data){

        try {
            $create = SlaTemplate::create($data);

            self::generateVersion($create);
            
            $return = new SlaTemplateResources($create);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(SlaTemplate $sla_template,$data){

        try {
            $update = $sla_template->update($data);

            self::generateVersion($sla_template);

            $return = new SlaTemplateResources($sla_template);

            return self::success('Success', $return);
        }
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function delete(SlaTemplate $sla_template){
        Sla::where('sla_template_id',$sla_template->id)->delete();
        SlaVersion::where('sla_template_id',$sla_template->id)->delete();

        $sla_template->delete();

        return true;
    }

    public static function generateVersion($data){

        $get_version = SlaVersion::where('sla_template_id',$data->id)->orderBy('version','desc')->first();
        
        $data_version['version'] = $get_version ? $get_version->version + 1 : 1;
        $data_version['sla_template_id'] = $data->id;
        $data_version['response_time'] = $data->response_time;
        $data_version['response_time_type'] = $data->response_time_type;
        $data_version['response_time_penalty'] = $data->response_time_penalty;
        $data_version['response_time_penalty_type'] = $data->response_time_penalty_type;

        $data_version['resolution_time'] = $data->resolution_time;
        $data_version['resolution_time_type'] = $data->resolution_time_type;
        $data_version['resolution_time_penalty'] = $data->resolution_time_penalty;
        $data_version['resolution_time_penalty_type'] = $data->resolution_time_penalty_type;

        $data_version['response_time_location'] = $data->response_time_location;
        $data_version['response_time_location_type'] = $data->response_time_location_type;
        $data_version['response_time_location_penalty'] = $data->response_time_location_penalty;
        $data_version['response_time_location_penalty_type'] = $data->response_time_location_penalty_type;

        $data_version['verify_resolution_time'] = $data->verify_resolution_time;
        $data_version['verify_resolution_time_type'] = $data->verify_resolution_time_type;
        $data_version['verify_resolution_time_penalty'] = $data->verify_resolution_time_penalty;
        $data_version['verify_resolution_time_penalty_type'] = $data->verify_resolution_time_penalty_type;
        
        $create = SlaVersion::create($data_version);
    }

    
}