<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Services\AuthServices;

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


    
    

   

}
