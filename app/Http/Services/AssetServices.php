<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiTrait;

class AssetServices
{
    use ApiTrait;

    public function createIncident($data){
        if($data->complaint_user_id){
            $data_asset["reporter_name"] =  $data->complaintUser?->name;
            $data_asset["phone_number"] =  $data->complaintUser?->phone_no;
            $data_asset["office_phone_number"] = null;
        }
        $data_asset["spec_batch_item_id"] = $data->asset_parent_id ? [$data->asset_parent_id] : json_decode($data->asset_component_id) ;
        $data_asset["incident_no"] =  $data->incident_no;
        $data_asset["incident_date"] = $data->incident_date?->format('Y-m-d'); 
        $data_asset["description"] =  $data->information;
        $data_asset["location_id"] =  null;
        $data_asset["ic_number"] =  Auth::user()->ic_no;
        $data_asset["status"] =  $data->status;
        $data_asset["category_id"] =  $data->incident_asset_type;
        $data_asset["lost_date"] =  $data->date_asset_loss?->format('Y-m-d');
        $data_asset["police_report_date"] =  $data->date_report_police?->format('Y-m-d');
        $data_asset["police_report_reference"] =  $data->report_police_no;

        // $call_api = $this->callApiAsset('ext/logIncidentFromHelpDesk','POST',$data_asset);
        $call_api = self::callApi('asset','ext/logIncidentFromHelpDesk','POST',$data_asset);

        return $call_api;
    }

    public function getAsset($id){
        $parameter['spec_batch_item_id'] = $id;

        $call_api = self::callApi('asset','ext/getAsset','GET',$parameter);

        if($call_api['data']){
            $data = $call_api['data'];

            return $data->data;
        }
        else{
            return [];
        }

    }
}