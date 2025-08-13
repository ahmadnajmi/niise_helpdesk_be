<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssetServices
{
    protected string $baseUrl;

    public function __construct(){
        $this->baseUrl = config('app.asset.url');
    }

    public function callApiAsset($api_url,$method,$json) {
        $client = new Client();

        $client = new Client(['base_uri' => $this->baseUrl]);

        $postData = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Client-ID' => config('app.asset.client_id'),
                'Client-Secret' => config('app.asset.client_secret'),
            ],
            'json' => $json
        ];

        
        Log::channel('api_log')->info("API Request: {$method},{$this->baseUrl}{$api_url}", [
            'body' => $json,
        ]);
        
        try{
            $call_api = $client->$method($api_url, $postData);
            $response = $call_api->getBody()->getContents();

            Log::channel('api_log')->info("API Response: {$call_api->getStatusCode()},{$this->baseUrl}{$api_url}", [
                'user_id' => Auth::user()?->id,
                'body' => $response,
            ]);

            

            return ['data' => json_decode($response),'status' =>true];

        } catch (\GuzzleHttp\Exception\BadResponseException $e){ 
            $message = 'Something went wrong on the server.Error Code = '. $e->getCode();

            Log::channel('api_log')->info("API Response: {$e->getCode()}, {$api_url}", [
                'message' => $e->getMessage(),
            ]);
            
            return ['data' => null,'status' =>null,'message' => $e->getMessage()];
        }

    }

    public function createIncident($data){
        $data_asset["spec_batch_item_id"] = $data->asset_parent_id ? [$data->asset_parent_id] : json_decode($data->asset_component_id) ;
        $data_asset["incident_no"] =  $data->incident_no;
        $data_asset["incident_date"] = $data->incident_date?->format('Y-m-d'); 
        $data_asset["reporter_name"] =  $data->complaint->name;
        $data_asset["phone_number"] =  $data->complaint->phone_no;
        $data_asset["office_phone_number"] = $data->complaint->office_phone_no;
        $data_asset["description"] =  $data->information;
        // $data_asset["ptj_code"] =  $data->branch->id;
        // $data_asset["branch_code"] =   $data->branch->id;
        $data_asset["location_id"] =  null;
        $data_asset["ic_number"] =  Auth::user()->getRawOriginal('ic_no');
        $data_asset["status"] =  $data->status;
        $data_asset["category_id"] =  $data->incident_asset_type;
        $data_asset["lost_date"] =  $data->date_asset_loss?->format('Y-m-d');
        $data_asset["police_report_date"] =  $data->date_report_police?->format('Y-m-d');
        $data_asset["police_report_reference"] =  $data->report_police_no;

        $call_api = $this->callApiAsset('ext/logIncidentFromHelpDesk','POST',$data_asset);

        return $call_api;
    }
}