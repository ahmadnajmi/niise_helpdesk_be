<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Imports\BranchImport;
use Maatwebsite\Excel\Facades\Excel;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branch')->truncate();
        // DB::table('user')->truncate();
        
        DB::statement("ALTER SEQUENCE BRANCH_ID_SEQ RESTART START WITH 1");
        // DB::statement("ALTER SEQUENCE USER_ID_SEQ RESTART START WITH 1");

        Excel::import(new BranchImport, 'database/seeders/excel/branch_niise.xlsx');
        // Excel::import(new UsersImport, 'database/seeders/excel/user_niise_baru.xlsx');
    }
}
