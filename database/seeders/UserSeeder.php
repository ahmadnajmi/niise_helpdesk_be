<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Models\User;
use App\Models\UserRole;
use Maatwebsite\Excel\Facades\Excel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        User::truncate();
        UserRole::truncate();
        
        // DB::statement("ALTER SEQUENCE USER_ROLE_ID_SEQ RESTART START WITH 1");

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE USERS_ID_SEQ RESTART START WITH 1");
        }

        Excel::import(new UsersImport, 'database/seeders/excel/user_niise_baru.xlsx');
    }
}
