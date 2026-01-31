<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\CategoryImport;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE CATEGORIES_ID_SEQ RESTART START WITH 1");
        }

        Excel::import(new CategoryImport, 'database/seeders/excel/category.xlsx');
    }
}
