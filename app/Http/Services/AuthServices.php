<?php

namespace App\Http\Services;
use App\Http\Traits\ResponseTrait;
use App\Http\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResources;
use App\Http\Services\TwoFactorServices;
use App\Models\User;
use App\Models\SsoSession;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\Module;
use App\Mail\ForgetPasswordEmail;

class AuthServices
{
    use ResponseTrait,ApiTrait;

    public static function loginSso($request){
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
            return self::error(__('user.message.user_invalid_credentials'));
        }
    }

    public static function login($request){
        $user = self::logAttemptLogin($request);

        if($user['status']){

            if(Auth::user()->two_fa_enabled){
                $data = [
                    'two_fa_enabled' => true,
                ];
            }
            else{
                $token = self::generatePassportToken($user['data']);

                if(!$token['status']) {
                    return self::error($token['message']);
                }

                $data = [
                    'two_fa_enabled' => false,
                    'user' => new UserResources(Auth::user()),
                    'token' => $token['data']->access_token,
                    'role' => UserRole::getUserDetails(),
                    'permission' => Permission::getPermission(),
                    'module' => Module::getUserDetails(),
                ];
            }
            return self::success('Success', $data);
        }
        else{
            return self::error($user['message']);
        }
    }

    public static function logAttemptLogin($request){
        $maxAttempts = 3;

        $user = User::where('ic_no', $request->ic_no)->first();

        if(!$user){
            return ['status' => false, 'message' => __('user.message.user_invalid_credentials')];
        }

        if($user->is_disabled){
            return ['status' => false, 'message' => __('user.message.user_disabled')];
        }

        $credentials = [
            'ic_no' => $request->ic_no,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $user->failed_attempts = 0 ;

            if(!$user->two_fa_enabled){
                $user->save();
                Auth::user()->tokens()->update(['revoked' => true]);
            }

            return ['status' => true, 'data' => $credentials];

        }
        else{
            $user->failed_attempts += 1;

            if($user->failed_attempts >= $maxAttempts){
                $user->is_disabled = true;
            }
            $user->save();

            $attemptsLeft = ($maxAttempts + 1) - $user->failed_attempts;

            return ['status' => false, 'message' => __('user.message.user_invalid_credentials').__('user.message.user_attempts_left').$attemptsLeft];
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

    public static function resetPassword($request){
        $get_user = User::where('ic_no',$request['ic_no'])->first();

        if($get_user){
            $clean_name = strtoupper(str_replace(' ', '', $get_user->name));  
            $first    = substr($clean_name, 0, 6);
            $last = substr($get_user->ic_no, -6);

            $data['password'] = Hash::make($first.$last);
            $data['first_time_password'] = true;

            $update = $get_user->update($data);

            if($get_user->email){
                Mail::to($get_user->email)->queue(new ForgetPasswordEmail());
            }

            return self::success('Success', $update);
        }
        else{
            return self::error('Reset Password Failed. Invalid Ic Number.');
        }
    }

    public static function updatePassword($request){
        
        if(Hash::check($request['old_password'],Auth::user()->password)){

            $user = Auth::user();
            $user->password = Hash::make($request['password']);
            $user->first_time_password = false;
            $user->save();

            return self::success('Success', true);
        }
        else{
            return self::error('Change Password Failed.Old Password Not same.');
        }
    }

    public static function generateQrCode(){

        try{
            $google2fa = new TwoFactorServices();

            $process_2fa = $google2fa->processTwoFactor();

            return self::success('Success', $process_2fa);
        }
        catch(\Exception $e){
            return self::error($e->getMessage());
        }
       

    }

    public static function verifyCode($code){
        $google2fa = new TwoFactorServices();

        $process_2fa = $google2fa->verifyCode($code);

        if($process_2fa){

            $data = new UserResources(Auth::user());

            return self::success('Success', $data);
        }
        else{
            return self::error('Failed');
        }

    }
}