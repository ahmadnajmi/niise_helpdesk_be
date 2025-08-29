<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActionCode;
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

        $action_codes = [
            [   
                "name" => "Penyaluran Insiden",
                "nickname" => "ESCL",
                'description' => 'Menyalurkan insiden kepada juruteknik atau kumpulan yang berkaitan',
                'send_email' => 1,
                'email_recipient_id' => 2,
            ],
            [   
                "name" => "Penyelesaian Sebenar",
                "nickname" => "ACTR",
                'description' => 'Log telah diselesaikan',
                'send_email' => 1,
                'email_recipient_id' => 2,
            ],
            [   
                "name" => "Tutup",
                "nickname" => "CLSD",
                'description' => 'Log insiden ditutup',
                'send_email' => 1,
                'email_recipient_id' => 1,
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
                'send_email' => 0,
            ],
        ];


        foreach($action_codes as $action_code){


            $create = ActionCode::create($action_code);

        }
    }
}
