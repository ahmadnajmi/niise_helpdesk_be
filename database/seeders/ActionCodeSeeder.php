<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionCode;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class ActionCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ACTION_CODES')->truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE ACTION_CODES_ID_SEQ RESTART START WITH 1");
        } 

        $frontliner = Role::where('role','FRONTLINER')->first()?->id;
        $contractor = Role::where('role','CONTRACTOR')->first()?->id;

        $action_codes = [
            [   
                "name" => "Penyaluran Insiden",
                "nickname" => "ESCL",
                'description' => 'Menyalurkan insiden kepada juruteknik atau kumpulan yang berkaitan',
                'send_email' => 1,
                'email_recipient_id' => 3,
                'role_id' => [$frontliner]
            ],
            [   
                "name" => "Penyelesaian Sebenar",
                "nickname" => "ACTR",
                'description' => 'Log telah diselesaikan',
                'send_email' => 1,
                'email_recipient_id' => 2,
                'role_id' => [$frontliner,$contractor]
            ],
            [   
                "name" => "Tutup",
                "nickname" => "CLSD",
                'description' => 'Log insiden ditutup',
                'send_email' => 0,
                'role_id' => [$frontliner]
            ],
            [   
                "name" => "Log insiden bermula",
                "nickname" => "INIT",
                'description' => 'Menunjukkan log insiden bermula',
                'send_email' => 0,
            ],
            [   
                "name" => "Kemaskini",
                "nickname" => "UPDT",
                'description' => 'Kemaskini status terkini',
                'send_email' => 1,
                'email_recipient_id' => 1,
                'role_id' => [$frontliner,$contractor]

            ],
            [
                "name" => "Verfiy",
                "nickname" => "VRFY",
                'description' => 'Log yang verify',
                'send_email' => 0,
                'role_id' => [$frontliner]
            ],
            [
                "name" => "Jurutera berada di lapangan",
                "nickname" => "ONSITE",
                'description' => 'Jurutera pergi ke lapangan untuk membaik pulih',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
            [
                "name" => "Dalam Tindakan",
                "nickname" => "PROG",
                'description' => 'Tindakan yang di ambil',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
            [
                "name" => "Kembalikan kepada Helpdesk ICT",
                "nickname" => "RETURN",
                'description' => 'Insiden yang di kembalikan kepada helpdek ICT',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
            [
                "name" => "Pengecualian",
                "nickname" => "DISC",
                'description' => 'Pengecualian yang singkat. Contoh : User tiada di lapangan, Gagal menghubungi user,  dan sebagainya.',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
            [
                "name" => "Pengecualian bermula",
                "nickname" => "STARTD",
                'description' => 'Pengecualian yang panjang. Contoh Memasang alat ganti, berlaku bencana alam, dan sebagainya.',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
            [
                "name" => "Pengecualian tamat",
                "nickname" => "STOPD",
                'description' => 'Pengecualian telah selesai.',
                'send_email' => 0,
                'role_id' => [$frontliner,$contractor]
            ],
        ];

        foreach($action_codes as $action_code){

            if(isset($action_code['role_id'])){
                $action_code['role_id'] = json_encode($action_code['role_id']);
            }

            $create = ActionCode::create($action_code);
        }
    }
}
