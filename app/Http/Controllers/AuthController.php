<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\IdentityManagement\User;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Log;
use GuzzleHttp\Client;
use Session;


class AuthController extends Controller
{
    use ResponseTrait;

    public function logoutWeb(Request $request)
    {
        Auth::logout();
        $request->session()->flush();

        return redirect()->route('welcome');
    }

    public function dashboard(Request $request){
        return view('dashboard');
    }

    public function loginweb(Request $request){

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if(Auth::attempt($credentials)){
            $token = $this->generateToken($credentials);

            if(!$token['status']) {
                return redirect()->back();
            }
            Session::put('bearer_token', $token['data']->access_token);
            Session::save();

            return redirect()->route('dashboard');
        }
        else{
            return redirect()->back();
        }

    }

    public function login(Request $request)
    {

        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if(Auth::attempt($credentials)){
            $token = $this->generateToken($credentials);

            if(!$token['status']) {
                return $this->error($token['message']);
            }
            
            $user = User::getUserDetails();

            $data = [
                'user' => $user,
                'token' => $token['data']->access_token,
                'role' => UserRole::getUserDetails(),
                'permission' => Permission::getUserDetails(),
                'module' => Module::getUserDetails(),
            ];

            return $this->success('Success', $data);
        }
        else{
            return $this->error('Login failed. Invalid credentials.');
        }
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key){
            $token->delete();
        });

        return $this->success('Success', null);
    }


    public function generateToken($credentials){
        $client = new Client();

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
                'username' => $credentials['email'],
                'password' => $credentials['password'],
            ]
        ];

        try{
            $response = $client->post(config('app.passport_token.login_url'), $postData)->getBody()->getContents();

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

    public function authDetails(){
        $data['user'] = User::getUserDetails();
        $data['role'] = UserRole::getUserDetails();
        $data['permission'] = Permission::getUserDetails();
        $data['module'] = Module::getUserDetails();
        $data['session_id'] = session()->getId();


        return $this->success('Success', $data);
    }
    
    public function loginAssetManagement(){
        $url = config('app.url_application.fe_am');
    }

    public function loginHelpDesk(){

        $url = config('app.url_application.fe_ifics');
        $token = Session::get('bearer_token');
        // dd($token);
        return redirect()->to($url."login/sso?token=$token");
    }

}
