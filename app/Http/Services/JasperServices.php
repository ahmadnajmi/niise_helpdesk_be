<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JasperServices
{
    protected string $baseUrl;

    public function __construct(){
        $this->baseUrl = config('app.jasper_server.url');
        $this->username = config('app.jasper_server.username');
        $this->password = config('app.jasper_server.password');
    }

    public function callApiJasper($api_url,$method = 'GET',$json = []) {
        $status = true;
        $message = $data = null;
        $status_code = 200;
        $path = null;

        $client = new Client();

        $client = new Client([
            'base_uri' => $this->baseUrl,
            'auth' => [ $this->username, $this->password],
            'verify' => false,
        ]);

        $postData = [
            'headers' => [
                'Accept' => 'application/pdf',
            ],
        ];

        if (strtoupper($method) === 'GET') {
            $postData['query'] = $json; 
        } else {
            $postData['json'] = $json; 
        }
        
        try{
            $call_api = $client->$method($api_url, $postData);
            $response = $call_api->getBody()->getContents();

            $status_code = $call_api->getStatusCode();

            $path = storage_path('app/public/TableReport.pdf');
            file_put_contents($path, $call_api->getBody());

            $data = json_decode($response);
            $action = 'info';
           
        } catch (\GuzzleHttp\Exception\BadResponseException $e){ 

            $action = 'error';
            $message = $e->getMessage();
        }

        Log::channel('external_api')->$action("JASPER SERVER API Response: {$status_code}, {$this->baseUrl}{$api_url}", [
            'payload' => $json,
            'body' => $data,
            'message' => $message
        ]);

        if($path){
            return response()->download($path);
        }

        


        return ['data' => $data,'status' => $status, 'message' => $message];

    }
}