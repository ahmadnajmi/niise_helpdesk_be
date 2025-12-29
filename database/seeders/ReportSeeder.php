<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Report;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Report::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE REPORT_ID_SEQ RESTART START WITH 1");
        } 

        $reports = [
            ["code" => "IDLE", "file_name" => "idle_report"],
            ["code" => "OUTSTANDING", "file_name" => "outstanding"],
            ["code" => "TO_BREACH","file_name" => "to_be_breach_report"],
            ["code" => "SLA_BREACH" ,"file_name" => "sla_breach_report"],
        ];

        foreach($reports as $report){

            $create = Report::create($report);
        }

    }
}
