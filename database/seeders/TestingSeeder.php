<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SlaTemplate;
use App\Models\Sla;
use App\Models\Complaint;
use App\Models\Incident;
use App\Models\SlaVersion;
use App\Models\IncidentResolution;
use App\Models\Workbasket;
use App\Models\OperatingTime;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SlaTemplate::truncate();
        SlaVersion::truncate();
        Sla::truncate();
        Complaint::truncate();
        Incident::truncate();
        IncidentResolution::truncate();
        Workbasket::truncate();
        OperatingTime::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE SLA_TEMPLATE_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_VERSION_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE COMPLAINT_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE INCIDENTS_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE INCIDENT_RESOLUTION_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE WORKBASKET_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE OPERATING_TIMES_ID_SEQ RESTART START WITH 1");

        } 

        $list_branch = Branch::pluck('id');

        foreach($list_branch as $branch_id){
            OperatingTime::factory()->count(1)->create(['branch_id' => $branch_id]);
        }

        SlaTemplate::factory()->count(10)->create();
        Sla::factory()->count(20)->create();
        Complaint::factory()->count(20)->create();
        
        Incident::factory()->count(5)->create(['status' => Incident::OPEN]);
        Incident::factory()->count(5)->create(['status' => Incident::RESOLVED]);
        Incident::factory()->count(5)->create(['status' => Incident::CLOSED]);
        Incident::factory()->count(5)->create(['status' => Incident::CANCEL_DUPLICATE]);
        Incident::factory()->count(20)->create(['status' => Incident::ON_HOLD]);


    }
}
