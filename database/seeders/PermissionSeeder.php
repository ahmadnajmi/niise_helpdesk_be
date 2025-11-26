<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Imports\PermissionImport;
use App\Models\Permission;
use App\Models\RolePermission;

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
    }
}
