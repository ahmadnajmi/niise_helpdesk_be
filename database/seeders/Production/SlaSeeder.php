<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SlaTemplate;
use App\Models\Sla;
use App\Models\SlaVersion;

class SlaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SlaTemplate::truncate();
        SlaVersion::truncate();
        Sla::truncate();
       
        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE SLA_TEMPLATE_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_VERSION_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE SLA_ID_SEQ RESTART START WITH 1");
        } 

        $sla_template = $this->createSlaTemplate();
        $sla_version = $this->createSlaVersion($sla_template);
        $sla = $this->createSLA($sla_template);
    }

    public function createSlaTemplate(){
        $slaTemplate = [
            'code' => 'ST0001',
            'severity_id' => 5,
            'company_id' => 41,
            'company_contract_id' => 1,
            'resolution_time' => '30',
            'resolution_time_type' => 3,
        ];

        $create = SlaTemplate::create($slaTemplate);

        return $create;
    }

    public function createSLA($sla_template){
       

        $data = [
            ['code' => 'MY DIGITAL ID01', 'category_id' => 52],
            ['code' => 'MALAYSIA DIGITAL ARRIVAL CARD (MDAC)01', 'category_id' => 51],
            ['code' => 'CADANGAN PENGGUNA01', 'category_id' => 50],
            ['code' => 'PERTANYAAN01', 'category_id' => 49],
            ['code' => 'DAFTAR AHLI KUMPULAN01', 'category_id' => 48],
            ['code' => 'REKOD PERGERAKAN01', 'category_id' => 47],
            ['code' => 'CAPAIAN APLIKASI01', 'category_id' => 46],
            ['code' => 'MAKLUMAT PENGGUNA01', 'category_id' => 45],
            ['code' => 'KOD QR01', 'category_id' => 44],
            ['code' => 'IMBASAN PASSPORT01', 'category_id' => 43],
            ['code' => 'PENDAFTARAN AKAUN01', 'category_id' => 42],
            ['code' => 'LOGIN APLIKASI01', 'category_id' => 41],
            ['code' => 'BUKAN WARGANEGARA01', 'category_id' => 40],
            ['code' => 'MY DIGITAL ID_W01', 'category_id' => 39],
            ['code' => 'MALAYSIA DIGITAL ARRIVAL CARD (MDAC)_W01', 'category_id' => 38],
            ['code' => 'CADANGAN PENGGUNA_W01', 'category_id' => 37],
            ['code' => 'PERTANYAAN_W01', 'category_id' => 36],
            ['code' => 'DAFTAR AHLI KUMPULAN_W01', 'category_id' => 35],
            ['code' => 'REKOD PERGERAKAN_W01', 'category_id' => 34],
            ['code' => 'CAPAIAN APLIKASI_W01', 'category_id' => 33],
            ['code' => 'MAKLUMAT PENGGUNA_W01', 'category_id' => 32],
            ['code' => 'KOD QR_W01', 'category_id' => 31],
            ['code' => 'IMBASAN PASSPORT_W01', 'category_id' => 30],
            ['code' => 'PENDAFTARAN AKAUN_W01', 'category_id' => 29],
            ['code' => 'LOGIN APLIKASI_W01', 'category_id' => 28],
            ['code' => 'WARGANEGARA01', 'category_id' => 27],
            ['code' => 'APLIKASI MUDAH ALIH MYNIISE01', 'category_id' => 26],
            ['code' => 'APLIKASI01', 'category_id' => 2],
            ['code' => 'NIISE01', 'category_id' => 1],
        ];

        foreach($data as $data_sla){
            $data_sla['sla_template_id'] =  $sla_template->id;
            $data_sla['branch_id'] = json_encode(['1000000']);




            $create = Sla::create($data_sla);
        }
    }

    public function createSlaVersion($sla_template){
        $get_version = SlaVersion::where('sla_template_id',$sla_template->id)->orderBy('version','desc')->first();
        
        $data_version['version'] = $get_version ? $get_version->version + 1 : 1;
        $data_version['sla_template_id'] = $sla_template->id;
        $data_version['response_time'] = $sla_template->response_time;
        $data_version['response_time_type'] = $sla_template->response_time_type;
        $data_version['response_time_penalty'] = $sla_template->response_time_penalty;
        $data_version['response_time_penalty_type'] = $sla_template->response_time_penalty_type;

        $data_version['resolution_time'] = $sla_template->resolution_time;
        $data_version['resolution_time_type'] = $sla_template->resolution_time_type;
        $data_version['resolution_time_penalty'] = $sla_template->resolution_time_penalty;
        $data_version['resolution_time_penalty_type'] = $sla_template->resolution_time_penalty_type;

        $data_version['response_time_location'] = $sla_template->response_time_location;
        $data_version['response_time_location_type'] = $sla_template->response_time_location_type;
        $data_version['response_time_location_penalty'] = $sla_template->response_time_location_penalty;
        $data_version['response_time_location_penalty_type'] = $sla_template->response_time_location_penalty_type;

        $data_version['verify_resolution_time'] = $sla_template->verify_resolution_time;
        $data_version['verify_resolution_time_type'] = $sla_template->verify_resolution_time_type;
        $data_version['verify_resolution_time_penalty'] = $sla_template->verify_resolution_time_penalty;
        $data_version['verify_resolution_time_penalty_type'] = $sla_template->verify_resolution_time_penalty_type;
        
        $create = SlaVersion::create($data_version);
    }
}
