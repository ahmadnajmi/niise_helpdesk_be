<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResources;
use App\Http\Collection\RoleCollection;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Http\Services\RoleServices;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Module;

class RoleController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $limit = $request->limit ? $request->limit : 15;

        $data =  Role::paginate($limit);

        return new RoleCollection($data);
    }

    public function store(RoleRequest $request){
        $data = $request->all();

        $data = RoleServices::create($data);
           
        return $data; 
    }

    public function show(Role $Role){
        $data = new RoleResources($Role);

        return $this->success('Success', $data);
    }

    public function update(RoleRequest $request, Role $role){
        $data = $request->all();

        $data = RoleServices::update($role,$data);

        return $data;
    }

    public function destroy(Role $role){

        RoleServices::delete($role);

        return $this->success('Success', null);
    }


    public function updateRolePermission(RolePermissionRequest $request){
        $data = $request->all();

        $data = RoleServices::updateRolePermission($data);
           
        return $data; 
    }
}
