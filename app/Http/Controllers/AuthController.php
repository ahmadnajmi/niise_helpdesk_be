<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Services\AuthServices;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResources;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseTrait;

    public function dashboard(Request $request){
        return view('dashboard');
    }

    public function login(Request $request){
        $data = AuthServices::login($request);
           
        return $data;  
    }

    public function verifyToken(Request $request){
        $data = AuthServices::verifyToken($request);
           
        return $data; 
    }

    public function getAuthDetails(){
        $data = new UserResources(Auth::user());

        return $this->success('Success', $data);
    }

    public function logout(){
       
        $data = AuthServices::logout();
           
        return $data; 
    }

    public function logoutCallback(Request $request){
       
        $data = AuthServices::logoutCallback($request);
           
        return $data; 
    }

    public function authToken(Request $request){
        $data = AuthServices::getToken();
           
        return $data; 
    }

    public function resetPassword(AuthRequest $request){
        $data = $request->all();

        $data = AuthServices::resetPassword($data);
           
        return $data;
    }

    public function updatePassword(AuthRequest $request){
        $data = $request->all();

        $data = AuthServices::updatePassword($data);
           
        return $data;
    }

    public function generateQrCode(){
        $data = AuthServices::generateQrCode();
           
        return $data;
    }

    public function verifyCode($code){
        $data = AuthServices::verifyCode($code);
           
        return $data;
    }

    public function disableTwoFactor(){

        $data = AuthServices::disableTwoFactor();
           
        return $data;
    }
}
