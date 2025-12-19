<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Resources\PermissionResources;
use App\Http\Collection\PermissionCollection;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;


class PermissionController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  Permission::paginate($limit);

        return new PermissionCollection($data);
    }

    public function store(PermissionRequest $request)
    {
        try {
            $data = $request->all();

            $create = Permission::create($data);
           
            $data = new PermissionResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Permission $permission)
    {
        $data = new PermissionResources($permission);

        return $this->success('Success', $data);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        try {
            $data = $request->all();

            $update = $permission->update($data);

            $data = new PermissionResources($permission);

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
