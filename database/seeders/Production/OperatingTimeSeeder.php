<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OperatingTime;


class OperatingTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['branch_id' => '1000003', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1010051', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1010052', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1007031', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1001231', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1000000', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1000002', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1000004', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
            ['branch_id' => '1000001', 'day_start' => 1, 'day_end' => 7, 'duration' => 1],
        ];

        foreach($data as $data_operating_time){
            OperatingTime::create($data_operating_time);
        }
    }
}
