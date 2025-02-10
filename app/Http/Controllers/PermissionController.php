<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\PermissionCollection;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;


class PermissionController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $data =  PermissionCollection::collection(Permission::paginate(15));

        return $this->success('Success', $data);
    }

    public function store(PermissionRequest $request)
    {
        try {
            $data = $request->all();

            $create = Permission::create($data);
           
            $data = new PermissionCollection($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Permission $permission)
    {
        $data = new PermissionCollection($permission);

        return $this->success('Success', $data);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        try {
            $data = $request->all();

            $update = $permission->update($data);

            $data = new PermissionCollection($permission);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return $this->success('Success', null);
    }
}
