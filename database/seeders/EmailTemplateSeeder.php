<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE EMAIL_TEMPLATES_ID_SEQ RESTART START WITH 1");
        } 

        $email_template = [
            [
                'name' => 'Default Template',
                'sender_name' => 'Helpdesk System',
                'sender_email' => 'ccc@heitech.com.my',
                'notes' => 'This email was sent from niise.jim.giv.my No signature required. Any feedback, please reply to ccc@heitech.com.my',
                'is_active' => true
            ]
        ];

        foreach($email_template as $data){

            $create = EmailTemplate::create($data);

        }
    }
}
