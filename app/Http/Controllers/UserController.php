<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Models\IdentityManagement\User;

class UserController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data =  UserCollection::collection(User::paginate(15));

        return $this->success('Success', $data);
    }

    public function show(User $user)
    {
        $data = new UserCollection($user);

        return $this->success('Success', $data);
    }

    public function testingJasper(){
        $data =  UserCollection::collection(User::paginate(15));

        return json_encode($data);
        return $this->success('Success', $data);
    }


}
