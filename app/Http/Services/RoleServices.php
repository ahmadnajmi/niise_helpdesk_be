<?php

namespace App\Http\Services;
use App\Http\Resources\CategoryResources;
use App\Http\Resources\RoleResources;
use App\Http\Traits\ResponseTrait;
use App\Models\RolePermission;
use  App\Models\Module;
use  App\Models\Permission;
use App\Models\Role;

class RoleServices
{
    use ResponseTrait;
    
    public static function create($data){
        try{
            $create = Role::create($data);
           
            $return = new RoleResources($create);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function update(Role $role,$data){

        try{
            $update = $role->update($data);

            if($data['is_allow'] == true){
                $exits = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$role->id)->exists();

                if(!$exits){
                    $data['role_id'] = $role->id;

                    $create = RolePermission::create($data);
                }
            }
            else{
                $delete = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$role->id)->delete();

                self::checkParentPermission();
            }

            $return = new RoleResources($role);

            return self::success('Success', $return);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }

    }

    public static function delete(Role $role){
        try{
            $role->delete();

            $delete_old_data = Permission::where('role_id',$role->id)->delete();

            return self::success('Success', true);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }
    }

    public static function updateRolePermission($data){
        $create = true;
        try{
            if($data['is_allow'] == true){
                $action = 'create';

                $exits = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$data['role_id'])->exists();

                if(!$exits){
                    $create = RolePermission::create($data);
                }
            }
            else{
                $delete = RolePermission::where('permission_id',$data['permission_id'])->where('role_id',$data['role_id'])->delete();
                $action = 'delete';
            }

            self::syncParentModulePermissions($data['permission_id'],$data['role_id'],$action);

            return self::success('Success', $create);
        } 
        catch (\Throwable $th) {
            return self::error($th->getMessage());
        }

    }

    public static function syncParentModulePermissions($permission_id, $role_id, $action){
        $permission = Permission::find($permission_id);

        if (!$permission) {
            return;
        }

        $module = Module::find($permission->module_id);

        if (!$module || !$module->module_id) {
            return; 
        }

        $parentPermission = Permission::where('module_id', $module->module_id)->first();

        if (!$parentPermission) {
            return;
        }

        if ($action === 'create') {

            RolePermission::firstOrCreate([
                'permission_id' => $parentPermission->id,
                'role_id' => $role_id
            ]);
            
            self::syncParentModulePermissions($parentPermission->id, $role_id, 'create');
            
        } 
        elseif ($action === 'delete') {
            $pair_module = Module::where('module_id', $module->module_id)->pluck('id');

            $parent_module = RolePermission::where('role_id', $role_id)
                                                    ->whereHas('permission', function ($query) use ($pair_module) {
                                                        $query->whereIn('module_id', $pair_module)->whereRaw('LOWER(name) LIKE ?', ["%index%"]);
                                                    })
                                                    ->exists();

            if (!$parent_module) {
                RolePermission::where('permission_id', $parentPermission->id)
                                ->where('role_id', $role_id)
                                ->delete();
                
                self::syncParentModulePermissions($parentPermission->id, $role_id, 'delete');
            }
        }
    }

 
}