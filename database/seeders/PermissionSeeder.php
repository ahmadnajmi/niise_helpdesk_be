<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Imports\PermissionImport;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Module;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::truncate();
        RolePermission::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE PERMISSIONS_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE ROLE_PERMISSIONS_ID_SEQ RESTART START WITH 1");

        } 

        Excel::import(new PermissionImport, 'database/seeders/excel/permission.xlsx');

        $role = ['BTMR','SUPER_ADMIN'];

        $module = Module::where('code','individuals')->first();

        $create_permission = Permission::create([
            'module_id' => $module->id,
            'name' => 'individual.reset-password',
            'description' => null,
        ]);

        foreach($role as $role){
            $get_role = Role::where('role',$role)->first();

            $data_permission['role_id'] = $get_role?->id;
            $data_permission['permission_id'] = $create_permission->id;

            RolePermission::create($data_permission);
        }
    }
}
