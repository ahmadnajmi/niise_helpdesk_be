<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::truncate();
        UserGroup::truncate();

        // if (DB::getDriverName() === 'oracle') {
        //     DB::statement("ALTER SEQUENCE USER_GROUPS_ID_SEQ RESTART START WITH 1");
        // }


        $datas[] = [
            'name' => 'IT Care',
            'description' => 'IT CARE TEAM ( HELPDESK)',            
        ];

        $datas[] = [
            'name' => 'IMPLEMENTATION ANALYST',
            'description' => 'IMPLEMENTATION ANALYST',
            'users' => ['810621105767','010311100324','010313131239','910324035019','010210040027','960301106131','010221060293','010807100475',
            '000721050076','000205070353','021113101493','850914105097','020804030511','020227140859','961215145884']
        ];

        $datas[] = [
            'name' => 'FRONTLINER HELPDESK ICT',
            'description' => 'FRONTLINER HELPDESK ICT',
            'users' => ['950327065340','001201050237','950314105489','970225025303','870321566005','000223101985',
                '860106525789','020124100993','000402060330','991105105870','010629011233','950806036221','980103036441',
                '810220105459','880530235121'
            ]
        ];

        foreach($datas as $data) {

            $data_group = $data;
            unset($data_group['users']);
            
            $create =  Group::create($data_group);


            if (isset($data['users'])){
                $data_user['groups_id'] = $create->id;

                foreach($data['users'] as $ic_no){

                    $get_user = User::where('ic_no',$ic_no)->first();
                    
                    $data_user['ic_no'] = $ic_no;
                    $data_user['user_type'] = 1;
                    $data_user['name'] = $get_user->name;
                    $data_user['email'] = $get_user->email;
                    $data_user['company_id'] = $get_user->company_id;

                    UserGroup::create($data_user);
                }

                
            }
        }
    }
}
