<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionCode;

class ActionCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('ACTION_CODES')->truncate();

        DB::statement("ALTER SEQUENCE ACTION_CODES_ID_SEQ RESTART START WITH 1");

        $categorys = ['MOBILE'];


        foreach($categorys as $category_code){

            $data['name'] = $category_code;
            $data['level'] = 1;
            $data['code'] = '01';
            $data['description'] = $category_code;

            $create = ActionCode::create($data);

        }
    }
}
