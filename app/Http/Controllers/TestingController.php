<?php

namespace App\Http\Controllers;
use App\Http\Services\NetIQSocialiteProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    //

    public function redirect(Request $request){
        $provider = new NetIQSocialiteProvider(
            app()->make('request'), 
            config('app.netiq.client_id'), 
            config('app.netiq.client_secret'), 
            config('app.netiq.redirect_url')
        );

        return redirect($provider->getRedirectUrl());
    }

    public function callback(Request $request){
        $provider = new NetIQSocialiteProvider(
            app()->make('request'), 
            config('app.netiq.client_id'), 
            config('app.netiq.client_secret'), 
            config('app.netiq.redirect_url')
        );

        $token = $provider->getAccessTokenResponse($request->input('code'));
        $userData = $provider->getUserByToken($token['access_token']);
        dd($userData,$token);
        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            ['name' => $userData['name'] ?? $userData['preferred_username'] ?? 'User']
        );

        Auth::login($user);

        return redirect('/home');
    }

    public function logout(){


        https://lab3.secure-x.my/nidp/oauth/v1/nam/end_session
    }
}
