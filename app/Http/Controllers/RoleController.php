<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\RoleCollection;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

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
           
            $create_permission = $this->createPermission($data,$create);

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

            $delete_old_data = Permission::where('role_id',$role->id)->delete();

            $create_permission = $this->createPermission($data,$role);

            $data = new RoleCollection($role);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        $role->delete();

        $delete_old_data = Permission::where('role_id',$role->id)->delete();

        return $this->success('Success', null);
    }

    public function createPermission($data,$create)
    {
        if(isset($data['permission'])){

            foreach($data['permission'] as $idx => $permission){

                $permission['role_id'] = $create->id;

                $create_permission = Permission::create($permission);
            }
        }
        return true;
    }

    public function updateRolePermission(RolePermissionRequest $request){

        try {
            $data = $request->all();
            $exits = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$data['role_id'])->exists();

            if($data['is_allow'] == true){
                if(!$exits){
                    $create = RolePermission::create($data);
                }
                else{
                    $create = true;
                }
            }
            else{
                $delete = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$data['role_id'])->delete();
                $create = true;
            }
            return $this->success('Success', $create);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

    }
}
