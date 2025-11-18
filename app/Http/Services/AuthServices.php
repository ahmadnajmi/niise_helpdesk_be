<?php

namespace App\Http\Services;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\SsoSession;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\Module;

class AuthServices
{
    use ResponseTrait;

    public static function login($request){

        if($request->ic_no){
            $user = User::where('ic_no', $request->ic_no)->first();

            if($user){
                Auth::login($user);

                self::storeSsoToken($request);

                $tokenResult = $user->createToken('NetIQSSO');
                $token = $tokenResult->accessToken;

                $data = [
                    'token' => $token,
                    'user' => User::getUserDetails(),
                    'role' => UserRole::getUserDetails(),
                    'permission' => Permission::getUserDetails(),
                    'module' => Module::getUserDetails(),
                ];

                return self::success('Success Netiq', $data);
            }
            else{
                return self::error('Login failed. Invalid credentials.');
            }
        }
        else{

            $user = User::where('email', $request->email)->first();

            $credentials = [
                'ic_no' => $user->ic_no,
                'password' => $request['password'],
            ];

            if(Auth::attempt($credentials)){
                $token = self::generateToken($credentials);

                if(!$token['status']) {
                    return self::error($token['message']);
                }

                $user = User::getUserDetails();

                $data = [
                    'user' => $user,
                    'token' => $token['data']->access_token,
                    'role' => UserRole::getUserDetails(),
                    'permission' => Permission::getUserDetails(),
                    'module' => Module::getUserDetails(),
                ];

                return self::success('Success', $data);
            }
            else{
                return self::error('Login failed. Invalid credentials.');
            }
        }
    }

    public static function storeSsoToken($request){

        if (!empty($request->id_token)) {
            $parts = explode('.', $request->id_token);

            if (count($parts) === 3) {
                $decoded = base64_decode(strtr($parts[1], '-_', '+/'), true);
                if ($decoded !== false) {
                    $payload = json_decode($decoded, true);
                    $sid = $payload['sid'] ?? null;
                }
            }
        }

        SsoSession::updateOrCreate(
            ['user_id' => Auth::user()->id],
            [
                'id_token' => $request->id_token,
                'access_token' => $request->access_token,
                'session_id' => $sid ?? null,
                'is_active' => true,
            ]
        );

        return true;

    }

    public static function generateToken($credentials){
        $client = new Client(['verify' => false]);

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

    public static function getToken(){
        $data = SsoSession::select('id_token','access_token')->where('user_id',Auth::user()->id)->first();

        return self::success('Success Get Token', $data);
    }

    public static function logout(){
        $delete = SsoSession::where('user_id',Auth::user()->id)->delete();
        Auth::user()->tokens->each(function ($token, $key){
            $token->delete();
        });

        return self::success('Success Logout', true);
    }

    public static function logoutCallback($request){
        Log::debug('start logout callback');
        Log::debug($request->all());
        
        $sso_session = SsoSession::where('session_id',$request->sid)->first();

        $user = User::find($sso_session?->user_id);

        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
        }

        $sso_session?->delete();

        Log::debug('end logout callback');
    }

    // public function authDetails(){
    //     $data['user'] = User::getUserDetails();
    //     $data['role'] = UserRole::getUserDetails();
    //     $data['permission'] = Permission::getUserDetails();
    //     $data['module'] = Module::getUserDetails();
    //     $data['session_id'] = session()->getId();


    //     return $this->success('Success', $data);
    // }


}