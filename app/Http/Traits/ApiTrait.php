<?php
namespace App\Http\Traits;

use Illuminate\Testing\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LogExternalApi;

trait ApiTrait {

    private static $client = null;

    private static function getClient($url){
        self::$client = new Client([
            'verify' => false, // disable SSL verify if needed
            'base_uri' => $url
        ]);
        
        return self::$client;
    }

    public static function callApi($function,$api_url,$method,$json) {

        if($function == LogExternalApi::ASSET){
            $url = config('app.asset.url');

            $postData = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Client-ID' => config('app.asset.client_id'),
                    'Client-Secret' => config('app.asset.client_secret'),
                ],
            ];
        }
        elseif($function == LogExternalApi::JASPER){
            $url = config('app.microservices.url');

        }

        $client = self::getClient($url);

        if($function == LogExternalApi::JASPER){
            $postData['multipart'] = $request = $json['multipart'];
        }
        elseif (strtoupper($method) === 'GET') {
            $postData['query'] = $request = $json; 
        } else {
            $postData['json'] = $request = $json; 
        }

        // Log::channel('external_api')->info("API Request: {$method},{$url}{$api_url}", [
        //     'body' => $json,
        // ]);
        try{
            $call_api = $client->$method($api_url, $postData);

            if($function == 'asset'){
                $response = json_decode($call_api->getBody()->getContents(),true);
            }
            else{
                $response = $call_api;
            }

            // Log::channel('external_api')->info("API Response: {$call_api->getStatusCode()},{$url}{$api_url}", [
            //     'user_id' => Auth::user()?->id,
            //     'body' => $response,
            // ]);

            self::logApiHelper([
                'service_name' => $function,
                'endpoint' => $url.$api_url,
                'is_success' => $call_api->getStatusCode() >= 200 && $call_api->getStatusCode() < 300,
                'status_code' => $call_api->getStatusCode(),
                'request' => json_encode($request),
                'response' => json_encode($response),
                'error_message' => $call_api->getStatusCode() >= 200 && $call_api->getStatusCode() < 300 ? null : $response,
            ]);

            if($function == LogExternalApi::JASPER){
                $contentType = self::getContentType($json['report_format']);
                $filename = $json['outputFileName'];

                if ($call_api->getStatusCode() >= 200 && $call_api->getStatusCode() < 300) {
                    return [
                        'data' => $response,
                        'filename' => $filename,
                        'contentType' => $contentType
                    ]; 
                }
                else{
                    return ['data' => null ,'status' => $response->status(),'message' => 'Failed to generate report'.$response->body()];
                }
            }
            else{
                return ['data' => $response['data'],'status' =>true];
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e){ 
            $message = 'Something went wrong on the server.Error Code = '. $e->getCode();

            Log::channel('external_api')->error("API Response: {$e->getCode()}, {$url}{$api_url}", [
                'message' => $e->getMessage(),
            ]);
            
            return ['data' => null,'status' =>null,'message' => $e->getMessage()];
        }

    }

    private static function getContentType($reportFormat) {
        switch($reportFormat) {
            case 'pdf':
                return 'application/pdf';
            case 'csv':
            case 'excel':
                return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            default:
                return 'application/pdf';
        }
    }

    public static function generatePassportToken($credentials){
        $client = self::getClient(config('app.passport_token.login_url'));

        $postData = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'url' => config('app.passport_token.login_url'),
                'grant_type' => 'password',
                'client_id' => config('app.passport_token.client_id'),
                'client_secret' => config('app.passport_token.client_secret'),
                'username' => $credentials['ic_no'],
                'password' => $credentials['password'],
            ]
        ];
        // dd($postData);
        try{
            $call_api = $client->post('', $postData);

            $response = $call_api->getBody()->getContents();

            return ['data' => json_decode($response),'status' =>true];

        } catch (\GuzzleHttp\Exception\BadResponseException $e){
            if ($e->getCode() == 400){
                $message = 'Invalid Request. Please enter a username or a password.Error Code = ' .$e->getCode();
            } else if ($e->getCode() == 401){
                $message = 'Your credentials are incorrect. Please try again.Error Code = ' .$e->getCode();
            }
            else{
                $message = 'Something went wrong on the server.Error Code = '. $e->getCode();
            }
            return ['data' => null,'status' =>null,'message' => $message];

        }
    }

    public static function logApiHelper($data){

        $log = [
            'service_name' => $data['service_name'],
            'endpoint' => $data['endpoint'],
            'is_success' => $data['is_success'],
            'status_code' => $data['status_code'],
            'request' => $data['request'],
            'response' => $data['response'],
            'error_message' => $data['error_message'],
        ];
        LogExternalApi::create($log);
    }
}


