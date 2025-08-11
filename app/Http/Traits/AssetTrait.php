<?php
namespace App\Http\Traits;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait AssetTrait {

    protected function url($url,$method,$json) {
        $client = new Client();

        $postData = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Client-ID' => config('app.asset.client_id'),
                'Client-Secret' => config('app.asset.client_secret'),
            ],
            'json' => $json
        ];

        
        Log::channel('api_log')->info("API Request: {$method}, {$url}", [
            'body' => $json,
        ]);
        
        try{
            $response = $client->$method(config('app.asset.url'), $postData)->getBody()->getContents();


            Log::channel('api_log')->info("API Response: {$response->getStatusCode()}, {$url}", [
                'user_id' => Auth::user()?->id,
                'headers' => $response->headers->all(),
                'body' => $response->getContent(),
            ]);

            return ['data' => json_decode($response),'status' =>true];

        } catch (\GuzzleHttp\Exception\BadResponseException $e){ 
            $message = 'Something went wrong on the server.Error Code = '. $e->getCode();

            Log::channel('api_log')->info("API Response: {$e->getCode()}, {$url}", [
                'message' => $e->getMessage(),
            ]);
            
            return ['data' => null,'status' =>null,'message' => $message];
        }

    }

    protected function createIncident($data){

        $data["incident_no"] =  $data->incident_no;
        $data["incident_date"] = $data->incident_date; 
        $data["reporter_name"] =  Auth::user()->id;
        $data["phone_number"] =  Auth::user();
        $data["office_phone_number"] =  "0196024579";
        $data["description"] =  "TOLONG LAPTOP SAYA HILANG.";
        $data["ptj_code"] =  null;
        $data["branch_code"] =  null;
        $data["location_id"] =  null;
        $data["ic_number"] =  "980101010101";
        $data["spec_batch_item_id"] =  61;
        $data["status"] =  1;
        $data["category_id"] =  2;
        $data["lost_date"] =  "2025-08-06";
        $data["police_report_date"] =  "2025-08-07";
        $data["police_report_reference"] =  "PDRM/2025/09876";



    }
}
