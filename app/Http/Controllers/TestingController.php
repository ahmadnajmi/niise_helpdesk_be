<?php

namespace App\Http\Controllers;
use App\Http\Services\NetIQSocialiteProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;

class TestingController extends Controller
{
    //
    use ResponseTrait;

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

        $data = $provider->getUserFromCode($request->input('code'));

        dd($data,$request->input('code'));
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

    public function testingJasper(){
        $data = Branch::select('id as branch_code','state_id as state','name','category','location')->get();

        return $this->success('Success', $data);
    }
}
