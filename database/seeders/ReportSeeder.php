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
            [
                "code" => "IDLE", 
                "jasper_file_name" => "idle_report",
                "output_name" => 'Laporan insiden tanpa tindakan'
            ],
            [
                "code" => "OUTSTANDING",
                "jasper_file_name" => "outstanding",
                "output_name" => 'Laporan belum selesai'
            ],
            [
                "code" => "TO_BREACH",
                "jasper_file_name" => "to_be_breach_report",
                "output_name" => 'Laporan akan melebihi masa sla'
            ],
            [
                "code" => "SLA_BREACH" ,
                "jasper_file_name" => "sla_breach_report",
                "output_name" => 'Laporan yang melebihi masa sla'
            ],
            [
                "code" => "STATUS" ,
                "jasper_file_name" => "status_report",
                "output_name" => 'Laporan Jumlah Insiden (Status)'
            ],

        ];

        foreach($reports as $report){

            $create = Report::create($report);
        }

    }
}
