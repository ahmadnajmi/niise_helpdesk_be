<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\RoleCollection;
use App\Http\Requests\RoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data =  RoleCollection::collection(Role::paginate(15));

        return $this->success('Success', $data);
    }

    public function store(RoleRequest $request)
    {
        try {
            $data = $request->all();
            
            $create = Role::create($data);
           
            $data = new RoleCollection($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Role $Role)
    {
        $data = new RoleCollection($Role);

        return $this->success('Success', $data);
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data = $request->all();

            $update = $role->update($data);

            $data = new RoleCollection($role);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return $this->success('Success', null);
    }
}
