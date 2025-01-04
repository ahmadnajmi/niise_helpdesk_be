<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseTrait;

    /**
     * Login
     */
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['ID' => $username, 'password' => $password])) {

            // $token = Auth::user()->createToken('authToken')->accessToken;

            $data = [
                'user' => Auth::user(),
                // 'token' => $token,
            ];

            return $this->success('Login successful.', $data);
        } else {
            return $this->error('Login failed. Invalid credentials.');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        return $this->success('Logout successful.');
    }

}
