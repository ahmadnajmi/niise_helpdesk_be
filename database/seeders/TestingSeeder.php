<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SlaTemplate;
use App\Models\Sla;
use App\Models\Complaint;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sla_template')->truncate();
        DB::table('sla_version')->truncate();
        DB::table('sla')->truncate();
        DB::table('incidents')->truncate();
        DB::table('incident_resolution')->truncate();
        DB::table('complaint')->truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE SLA_TEMPLATE_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_VERSION_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE COMPLAINT_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE INCIDENTS_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE INCIDENT_RESOLUTION_ID_SEQ RESTART START WITH 1");
        } 

        SlaTemplate::factory()->count(10)->create();
        Sla::factory()->count(20)->create();
        Incident::factory()->count(5)->create(['status' => Incident::OPEN]);
        Incident::factory()->count(5)->create(['status' => Incident::RESOLVED]);
        Incident::factory()->count(5)->create(['status' => Incident::CLOSED]);
        Incident::factory()->count(5)->create(['status' => Incident::CANCEL_DUPLICATE]);
        Incident::factory()->count(5)->create(['status' => Incident::ON_HOLD]);


    }
}
