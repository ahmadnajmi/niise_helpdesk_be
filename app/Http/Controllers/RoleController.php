<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResources;
use App\Http\Collection\RoleCollection;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RolePermissionRequest;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Module;

class RoleController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;

        $data =  Role::paginate($limit);

        return new RoleCollection($data);
    }

    public function store(RoleRequest $request)
    {
        try {
            $data = $request->all();

            $create = Role::create($data);
           
            $data = new RoleResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Role $Role)
    {
        $data = new RoleResources($Role);

        return $this->success('Success', $data);
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $data = $request->all();

            $update = $role->update($data);

            if(isset($data['is_allow'])){
                if($data['is_allow'] == true){
                    $exits = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$role->id)->exists();
    
                    if(!$exits){
                        $data['role_id'] = $role->id;
    
                        $create = RolePermission::create($data);
                    }
                }
                else{
                    $delete = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$role->id)->delete();
                }
            }
            
            $data = new RoleResources($role);

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

                $this->updateParentModulePermission($data['permission_id'],$data['role_id'],true);
            }
            else{
                $delete = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$data['role_id'])->delete();
                $create = true;

                $this->updateParentModulePermission($data['permission_id'],$data['role_id']);

            }

            
            return $this->success('Success', $create);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

    }
    
    public function updateParentModulePermission($permission_id,$role_id,$action = null){

        $get_module = Permission::where('id',$permission_id)->first();

        $check_parent_module = Module::where('id',$get_module->module_id)->first();

        if($check_parent_module->module_id){

            $permission_parent  = Permission::where('module_id',$check_parent_module->module_id)->where('name','index')->first();

            $this->updateParentModulePermission($permission_parent->id,$role_id,$action);

            $check_permission_exist = RolePermission::where('permission_id',$permission_parent->id)->where('role_id',$role_id)->first();


            if($check_permission_exist && !$action){

                $check_other_sub_module = RolePermission::where('role_id',$role_id)
                                                        ->whereHas('permission', function ($query)use($check_parent_module) {
                                                            $query->where('module_id',$check_parent_module->module_id); 
                                                        })
                                                        ->exists();

                if(!$check_other_sub_module) $check_permission_exist->delete();
            }
            elseif(!$check_permission_exist && $action){

                $data_parent['permission_id'] = $permission_parent->id;
                $data_parent['role_id'] = $role_id;

                RolePermission::create($data_parent);
            }
        }

        return true;

    }
}
