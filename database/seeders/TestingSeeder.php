<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SlaTemplate;
use App\Models\Sla;
use App\Http\Services\SlaTemplateServices;
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

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE SLA_TEMPLATE_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_VERSION_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_ID_SEQ RESTART START WITH 1");
        } 

        SlaTemplate::factory()->count(10)->create();
        Sla::factory()->count(20)->create();

    }
}
