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
            ["code" => "IDLE", "file_name" => "unattendedDailyReport"],
            ["code" => "OUTSTANDING", "file_name" => "OutstandingReport"],
            ["code" => "TO_BREACH","file_name" => "tobebreached"],
            ["code" => "STATUS" ,"file_name" => "tobebreached"],
        ];

        foreach($reports as $report){

            $create = Report::create($report);
        }

    }
}
