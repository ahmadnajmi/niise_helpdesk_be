<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EmailTestingRequest;
use Webklex\IMAP\Facades\Client;
use App\Mail\TestMail; 

class TestingController extends Controller
{
    use ResponseTrait;

    public function testEmail(EmailTestingRequest $request){
        $email = $request->email;
        
        Mail::to($email)->send(new TestMail());

        return $this->success('Success', true);
    }

    public function testImap(){
        try{
            $client = Client::account('default');  

            $client->connect();

            $folder = $client->getFolder('INBOX');

            $query = $folder->messages()->all();

            $count = $query->count();

            return $this->success('Success', ['count' => $count]);

        } catch (\Throwable $e) {
            return $this->error('IMAP connection failed. '. $e->getMessage());
        }
       
    }
}
