<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Module;
use App\Models\UserRole;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->truncate();
        DB::table('role_permissions')->truncate();
        DB::table('user_role')->truncate();

        DB::statement("ALTER SEQUENCE ROLE_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $roles = [
            [
                'name' => 'Kakitangan JIM yang berkelayakan (Pengadu)',
                'name_en' => 'Qualified Immigration Officer (Complainant)',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' => [
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>[
                            'dashboard.index',

                            'dashboard.card.total-incidents',
                            'dashboard.card.total-sla',
                            'dashboard.card.total-reports',
                        ]
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'NOC /SOC / AOC (2nd Lvl)',
                'name_en' => 'NOC /SOC / AOC (2nd Lvl)',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>[
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                        ]
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>[
                            'audit-trail.index',
                            'audit-trail.view',
                        ]
                    ],
                    [
                        'module' => 'Knowledge Entries',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>[
                            'dashboard.index'
                        ]
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Jurutera HDS ICT Frontliner',
                'name_en' => 'HDS ICT Frontliner Engineer',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>[
                            'incident.index.all',
                            'incident.create',
                            'incident.view',
                            'incident.update',
                        ]
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>[
                            'audit-trail.index',
                            'audit-trail.view',
                        ]
                    ],
                    [
                        'module' => 'Knowledge Entries',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>['dashboard.index']
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Penyelia Helpdesk ICT',
                'name_en' => 'ICT Helpdesk Supervisor',
                'permission' =>  [
                    [
                        'module' => 'Pengurusan Individu',
                        'permission' =>[
                            'individual.index',
                            'individual.create',
                            'individual.view',
                            'individual.update',
                            'individual.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kumpulan',
                        'permission' =>[
                            'group.index',
                            'group.create',
                            'group.view',
                            'group.update',
                            'group.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Peranan',
                        'permission' =>[
                            'role.index',
                            // 'role.create',
                            'role.view',
                            // 'role.update',
                            // 'role.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kalendar',
                        'permission' =>[
                            'calendar.index',
                            'calendar.create',
                            'calendar.view',
                            'calendar.update',
                            'calendar.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Masa Operasi',
                        'permission' =>[
                            'operation-time.index',
                            'operation-time.create',
                            'operation-time.view',
                            'operation-time.update',
                            'operation-time.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kategori',
                        'permission' =>[
                            'category.index',
                            'category.create',
                            'category.view',
                            'category.update',
                            'category.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Format Email',
                        'permission' =>[
                            'email-format.index',
                            'email-format.create',
                            'email-format.view',
                            'email-format.update',
                            'email-format.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan Templat',
                        'permission' =>[
                            'sla-template.index',
                            'sla-template.create',
                            'sla-template.view',
                            'sla-template.update',
                            'sla-template.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan SLA',
                        'permission' =>[
                            'sla.index',
                            'sla.create',
                            'sla.view',
                            'sla.update',
                            'sla.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan Global',
                        'permission' =>[
                            'global-setting.index',
                            'global-setting.create',
                            'global-setting.view',
                            'global-setting.update',
                            'global-setting.delete',
                        ]
                    ],
                    [
                        'module' => 'Kod Tindakan',
                        'permission' =>[
                            'action-code.index',
                            'action-code.create',
                            'action-code.view',
                            'action-code.update',
                            'action-code.delete',
                        ]
                    ],
                    [
                        'module' => 'Modul',
                        'permission' =>[
                            'module.index',
                            // 'module.create',
                            'module.view',
                            'module.update',
                            // 'module.delete',
                        ]
                    ],
                    [
                        'module' =>'Insiden',
                        'permission' =>[
                            'incident.index.all',
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                            'incident.update',
                            // 'incident.delete',
                        ]
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>[
                            'audit-trail.index',
                            // 'audit-trail.create',
                            'audit-trail.view',
                            // 'audit-trail.update',
                            // 'audit-trail.delete',
                        ]
                    ],
                    [
                        'module' => 'Laporan',
                        'permission' =>[
                            'report.index',
                            'report.generate',
                            // -- more from report --
                        ]
                    ],
                    [
                        'module' =>'Knowledge Entries',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>['dashboard.index']
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Pentadbir Helpdesk Sistem (BTMR)',
                'name_en' => 'System Helpdesk Administrator (BTMR)',
                'permission' =>  [
                    [
                        'module' => 'Pengurusan Individu',
                        'permission' =>[
                            'individual.index',
                            'individual.create',
                            'individual.view',
                            'individual.update',
                            'individual.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kumpulan',
                        'permission' =>[
                            'group.index',
                            'group.create',
                            'group.view',
                            'group.update',
                            'group.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Peranan',
                        'permission' =>[
                            'role.index',
                            // 'role.create',
                            'role.view',
                            // 'role.update',
                            // 'role.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kalendar',
                        'permission' =>[
                            'calendar.index',
                            'calendar.create',
                            'calendar.view',
                            'calendar.update',
                            'calendar.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Masa Operasi',
                        'permission' =>[
                            'operation-time.index',
                            'operation-time.create',
                            'operation-time.view',
                            'operation-time.update',
                            'operation-time.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Kategori',
                        'permission' =>[
                            'category.index',
                            'category.create',
                            'category.view',
                            'category.update',
                            'category.delete',
                        ]
                    ],
                    [
                        'module' => 'Pengurusan Format Email',
                        'permission' =>[
                            'email-format.index',
                            'email-format.create',
                            'email-format.view',
                            'email-format.update',
                            'email-format.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan Templat',
                        'permission' =>[
                            'sla-template.index',
                            'sla-template.create',
                            'sla-template.view',
                            'sla-template.update',
                            'sla-template.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan SLA',
                        'permission' =>[
                            'sla.index',
                            'sla.create',
                            'sla.view',
                            'sla.update',
                            'sla.delete',
                        ]
                    ],
                    [
                        'module' => 'Tetapan Global',
                        'permission' =>[
                            'global-setting.index',
                            'global-setting.create',
                            'global-setting.view',
                            'global-setting.update',
                            'global-setting.delete',
                        ]
                    ],
                    [
                        'module' => 'Kod Tindakan',
                        'permission' =>[
                            'action-code.index',
                            'action-code.create',
                            'action-code.view',
                            'action-code.update',
                            'action-code.delete',
                        ]
                    ],
                    [
                        'module' => 'Modul',
                        'permission' =>[
                            'module.index',
                            // 'module.create',
                            'module.view',
                            'module.update',
                            // 'module.delete',
                        ]
                    ],
                    [
                        'module' =>'Insiden',
                        'permission' =>[
                            'incident.index.all',
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                            'incident.update',
                            // 'incident.delete',
                        ]
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>[
                            'audit-trail.index',
                            // 'audit-trail.create',
                            'audit-trail.view',
                            // 'audit-trail.update',
                            // 'audit-trail.delete',
                        ]
                    ],
                    [
                        'module' => 'Laporan',
                        'permission' =>[
                            'report.index',
                            'report.generate',
                            // -- more from report --
                        ]
                    ],
                    [
                        'module' =>'Knowledge Entries',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>['dashboard.index']
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],

            [
                'name' => 'Kontraktor',
                'name_en' => 'Contractor',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>[
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                        ]
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>[
                            'audit-trail.index',
                            'audit-trail.view',

                        ]
                    ],
                    [
                        'module' => 'Laporan',
                        'permission' =>[
                            'report.index',
                            'report.generate',
                            // -- more from report --
                        ]
                    ],
                    [
                        'module' => 'Halaman Utama',
                        'permission' =>['dashboard.index']
                    ],
                    [
                        'module' => 'Emel',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],
        ];


        foreach($roles as $role){

            $data_role['name'] = $role['name'];
            $data_role['name_en'] = $role['name_en'];
            $data_role['description'] = $faker->realText(100);

            $create = Role::create($data_role);

            $this->createRoleUser($create->id);

            $data_role_permission['role_id'] = $create->id;


            if(isset($role['permission'])){
                foreach($role['permission'] as $permission){

                    $module = Module::where('name',$permission['module'])->first();

                    if(!isset($module))dd($permission);
                    if($module->module_id){

                        $get_sub_module = Module::where('id',$module->module_id)->first();

                        $this->createPermissionRole($module->module_id,'index',$data_role_permission);

                        if($get_sub_module->module_id) {
                            $this->createPermissionRole($get_sub_module->module_id,'index',$data_role_permission);
                        }
                    }

                    foreach($permission['permission'] as $access_permission){
                        $this->createPermissionRole($module->id,$access_permission,$data_role_permission);
                    }
                }
            }
        }
    }

    public function createPermissionRole($module_id,$access_permission,$data_role_permission){

        $get_permission = Permission::where('module_id',$module_id)->where('name',$access_permission)->first();

        if(!$get_permission)dd($module_id,$access_permission);

        $data_role_permission['permission_id'] = $get_permission->id;

        $check_role_permission = RolePermission::where('permission_id',$data_role_permission['permission_id'])->where('role_id',$data_role_permission['role_id'])->exists();

        if(!$check_role_permission){
            $create_permission = RolePermission::create($data_role_permission);
        }
    }

    public function createRoleUser($role_id){
        foreach(range(1,104) as $user){
            $data['user_id'] = $user;
            $data['role_id'] = $role_id;

            $create = UserRole::create($data);
        }
    }
}
