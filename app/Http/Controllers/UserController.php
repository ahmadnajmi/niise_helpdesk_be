<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Collection\UserCollection;
use App\Http\Resources\UserResources;
use App\Http\Requests\UserRequest;
use App\Http\Services\UserServices;
use App\Models\User;

class UserController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  User::paginate($limit);

        return new UserCollection($data);
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->all();

            $create = UserServices::create($data);
           
            $data = new UserResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(User $user)
    {
        $data = new UserResources($user);

        return $this->success('Success', $data);
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $data = $request->all();

            $update = UserServices::update($user,$data);

            $data = new UserResources($user);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        UserServices::delete($user);

        return $this->success('Success', null);
    }

    public function searchIcNo(Request $request){

        $data = UserServices::searchIcNo($request);

        return $this->success('Success', $data);

    }

}
