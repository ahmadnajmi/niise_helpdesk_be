<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\RolePermission;
use App\Models\Permission;
use App\Models\Module;
use App\Models\Role;

class PermissionImport implements ToModel
{
    public function model(array $row)
    {
        $get_module = Module::where('code',$row[0])->first();

        // $list_permission[] = isset($row[2]) ? 'index' : null;
        // $list_permission[] = isset($row[3]) ? 'create' : null;
        // $list_permission[] = isset($row[4]) ? 'view' : null;
        // $list_permission[] = isset($row[5]) ? 'update' : null;
        // $list_permission[] = isset($row[6]) ? 'delete' : null;
        // $list_permission[] = isset($row[7]) ? 'replicate' : null;
        // $list_permission[] = isset($row[8]) ? 'generate' : null;
        // $list_permission[] = isset($row[9]) ? 'internal' : null;

        $actions = ['index','create','view','update','delete','replicate','generate','internal'];
        $list_permission = [];

        foreach ($actions as $key => $action) {
            $col = $key + 2;

            if (!empty($row[$col])) {
                $list_permission[] = $action;
            }
        }

        foreach($list_permission as $permission){
            $list_role = null;
            
            $data['module_id'] = $get_module?->id;
            $data['name'] = $row[1].'.'.$permission;
            $data['description'] = null;
        
            $create_permission = Permission::create($data);

            if($permission == 'index'){
                $list_role = isset($row[2]) ? $row[2] : null;
            }
            elseif($permission == 'create'){
                $list_role = isset($row[3]) ? $row[3] : null;
            }
            elseif($permission == 'view'){
                $list_role = isset($row[4]) ? $row[4] : null;
            }
            elseif($permission == 'update'){
                $list_role = isset($row[5]) ? $row[5] : null;
            }
            elseif($permission == 'delete'){
                $list_role = isset($row[6]) ? $row[6] : null;
            }
            elseif($permission == 'replicate'){
                $list_role = isset($row[7]) ? $row[7] : null;
            }
            elseif($permission == 'generate'){
                $list_role = isset($row[8]) ? $row[8] : null;
            }
            elseif($permission == 'internal'){
                $list_role = isset($row[9]) ? $row[9] : null;
            }

            if(isset($list_role)){
                $list_role = explode(',', $list_role);

                foreach($list_role as $role){

                    $get_role = Role::where('role',$role)->first();

                    $data_permission['role_id'] = $get_role?->id;
                    $data_permission['permission_id'] = $create_permission->id;

                    RolePermission::create($data_permission);
                }
            }
        }

    }
}
