<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::truncate();

        $data['name'] = 'IT Care';

        Group::create($data);
    }
}
