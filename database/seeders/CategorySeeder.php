<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CATEGORIES')->truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE CATEGORIES_ID_SEQ RESTART START WITH 1");
        } 

        $categorys = ['MOBILE'];


        foreach($categorys as $category_code){

            $data['name'] = $category_code;
            $data['level'] = 1;
            $data['code'] = '01';
            $data['description'] = $category_code;

            $create = Category::create($data);

        }



    }
}
