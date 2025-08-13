<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Imports\BranchImport;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branch')->truncate();
        DB::statement("ALTER SEQUENCE BRANCH_ID_SEQ RESTART START WITH 1");
        Excel::import(new BranchImport, 'database/seeders/excel/branch_niise.xlsx');

    }
}
