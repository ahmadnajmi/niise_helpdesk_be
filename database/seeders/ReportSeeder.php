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
                "name" => 'Idle Report',
                "code" => "IDLE", 
                "path" => "report/idle_report/idle_report.jasper",
                "file_name" => "idle_report.jasper",
                "output_name" => 'Laporan insiden tanpa tindakan'
            ],
            [
                "name" => 'Outstanding Report',
                "code" => "OUTSTANDING",
                "path" => "report/outstanding/outstanding.jasper",
                "file_name" => "outstanding.jasper",
                "output_name" => 'Laporan belum selesai'
            ],
            [
                "name" => 'To Be Breach Report',
                "code" => "TO_BREACH",
                "path" => "report/to_be_breach_report/to_be_breach_report.jasper",
                "file_name" => "to_be_breach_report.jasper",
                "output_name" => 'Laporan akan melebihi masa sla'
            ],
            [
                "name" => 'SLA Breach Report',
                "code" => "SLA_BREACH" ,
                "path" => "report/sla_breach_report/sla_breach_report.jasper",
                "file_name" => "sla_breach_report.jasper",
                "output_name" => 'Laporan yang melebihi masa sla'
            ],
            [
                "name" => 'Status Report',
                "code" => "STATUS" ,
                "path" => "report/status_report/status_report.jasper",
                "file_name" => "status_report.jasper",
                "output_name" => 'Laporan Jumlah Insiden (Status)'
            ],

        ];

        foreach($reports as $report){

            $report['is_default'] = true;

            $create = Report::create($report);
        }

    }
}
