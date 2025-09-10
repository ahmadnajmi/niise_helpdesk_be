<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Services\CategoryServices;

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

        $categorys = [
            [
                'category' => 'MOBILE',
                'sub_category' => ['MOBILE ANDROID','MOBILE IPHONE']
            ],
            [
                'category' => 'SISTEM',
                'sub_category' => ['SISTEM A','SISTEM B']
            ]
        ];

        foreach($categorys as $category){

            $create = $this->createCategory($category['category']);

            if(isset($category['sub_category'])){

                foreach($category['sub_category'] as $sub_category){

                    $this->createCategory($sub_category,$create->id);
                }
            }
        }
    }
    
    public function createCategory($category_name,$category_id = null){

        $data['level'] = 1;
        $data['code'] = '01';

        if($category_id){

            $main_category = Category::where('id',$category_id)->first();

            $data['category_id'] = $category_id;
            $data['level'] = $main_category->level + 1;

            $data['code'] = CategoryServices::getCode($data,$main_category->code);
        }

        $data['name'] = $category_name;
        
        $data['description'] = $category_name;

        $create = Category::create($data);

        return $create;
    }
}
