<?php
namespace App\Http\Traits;

use Illuminate\Testing\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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

        if($function == 'asset'){
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
        elseif($function == 'jasper'){
            $url = config('app.microservices.url');

        }

        $client = self::getClient($url);

        if (strtoupper($method) === 'GET') {
            $postData['query'] = $json; 
        } else {
            $postData['json'] = $json; 
        }

        Log::channel('external_api')->info("API Request: {$method},{$url}{$api_url}", [
            'body' => $json,
        ]);
        
        try{
            $call_api = $client->$method($api_url, $postData);

            if($function == 'asset'){
                $response = json_decode($call_api->getBody()->getContents(),true);
            }
            else{
                $response = $call_api;
            }

            Log::channel('external_api')->info("API Response: {$call_api->getStatusCode()},{$url}{$api_url}", [
                'user_id' => Auth::user()?->id,
                'body' => $response,
            ]);

            if($function == 'jasper'){
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
}


