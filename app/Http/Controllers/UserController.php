<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Collection\UserCollection;
use App\Http\Resources\UserResources;
use App\Models\IdentityManagement\User;

class UserController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  User::filter()->paginate($limit);

        return new UserCollection($data);
    }

    public function show(User $user)
    {
        $data = new UserResources($user);

        return $this->success('Success', $data);
    }

    public function testingJasper(){
        $data =  UserResources::collection(User::paginate(15));
        return response()->json($data,200);

        return json_encode($data);
        return $this->success('Success', $data);
    }


}
