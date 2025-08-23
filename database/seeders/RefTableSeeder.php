<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\RefTableImport;
use Maatwebsite\Excel\Facades\Excel;

class RefTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('ref_table')->truncate();
        
        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE REF_TABLE_ID_SEQ RESTART START WITH 1");
        } 

        Excel::import(new RefTableImport, 'database/seeders/excel/ref_table.xlsx');

    }
}
