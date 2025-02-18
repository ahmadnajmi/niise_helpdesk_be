<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Imports\BranchImport;
use App\Models\IdentityManagement\User;
use App\Models\IdentityManagement\Branch;
use Maatwebsite\Excel\Facades\Excel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('oracle_identity_management')->table('branch')->truncate();
        DB::connection('oracle_identity_management')->table('users')->truncate();
        
        DB::connection('oracle_identity_management')->statement("ALTER SEQUENCE BRANCH_ID_SEQ RESTART START WITH 1");
        DB::connection('oracle_identity_management')->statement("ALTER SEQUENCE USERS_ID_SEQ RESTART START WITH 1");


        Excel::import(new BranchImport, storage_path('app/private/branch_niise.xlsx'));

        Excel::import(new UsersImport, storage_path('app/private/user_niise.xlsx'));
    }
}
