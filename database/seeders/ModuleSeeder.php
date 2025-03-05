<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Permission;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::setDefaultConnection('oracle');

        DB::table('module')->truncate();
        DB::statement("ALTER SEQUENCE MODULE_ID_SEQ RESTART START WITH 1");
        DB::table('permissions')->truncate();
        DB::statement("ALTER SEQUENCE PERMISSIONS_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $modules = [
            [
                'module' => 'Halaman Utama',
                'name_en' =>'Dashboard',
                'permission' =>[
                    'dashboard.index',

                    'dashboard.card.total-incidents',
                    'dashboard.card.total-sla',
                    'dashboard.card.total-reports',

                    'dashboard.idle-incidents.index',

                    'dashboard.total-incidents-created.grand-total-this-year',
                    'dashboard.total-incidents-created.monthly.all',
                    'dashboard.total-incidents-created.monthly.self',

                    'dashboard.total-incidents-created.grand-total-this-month',
                    'dashboard.total-incidents-created.daily.all',
                    'dashboard.total-incidents-created.daily.self',

                    'dashboard.total-incidents-created.grand-total-today',
                    'dashboard.total-incidents-created.hourly.all',
                    'dashboard.total-incidents-created.hourly.self',
                ],
                'svg_path' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',

            ],
            [
                'module' => 'Pentadbiran Sistem',
                'name_en' => 'System Administration',
                'permission' => [],
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />',
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'name_en' => 'People Management',
                        'permission' => [],
                        'svg_path' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                        'lower_sub_module' => [
                            [
                                'name' => 'Pengurusan Individu',
                                'name_en' => 'Individual Management',
                                'permission' =>[
                                    'individual.index',
                                    'individual.create',
                                    'individual.view',
                                    'individual.update',
                                    'individual.delete',
                                ]
                            ],
                            [
                                'name' => 'Pengurusan Kumpulan',
                                'name_en' => 'Group Management',
                                'permission' =>[
                                    'group.index',
                                    'group.create',
                                    'group.view',
                                    'group.update',
                                    'group.delete',
                                ]
                            ],
                            [
                                'name' => 'Pengurusan Peranan',
                                'name_en' => 'Role Management',
                                'permission' =>[
                                    'role.index',
                                    // 'role.create',
                                    'role.view',
                                    // 'role.update',
                                    // 'role.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'name' =>'Pengurusan Operasi',
                        'name_en' =>'Operations Management',
                        'permission' => [],
                        'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />',
                        'lower_sub_module' => [
                            [
                                'name' => 'Pengurusan Kalendar',
                                'name_en' =>'Calendar Management',
                                'permission' =>[
                                    'calendar.index',
                                    'calendar.create',
                                    'calendar.view',
                                    'calendar.update',
                                    'calendar.delete',
                                ]
                            ],
                            [
                                'name' => 'Pengurusan Masa Operasi',
                                'name_en' =>'Operation Time Management',
                                'permission' =>[
                                    'operation-time.index',
                                    'operation-time.create',
                                    'operation-time.view',
                                    'operation-time.update',
                                    'operation-time.delete',
                                ]
                            ],
                            [
                                'name' => 'Pengurusan Kategori',
                                'name_en' =>'Category Management',
                                'permission' =>[
                                    'category.index',
                                    'category.create',
                                    'category.view',
                                    'category.update',
                                    'category.delete',
                                ]
                            ],
                            [
                                'name' => 'Pengurusan Format Email',
                                'name_en' =>'Email Format Management',
                                'permission' =>[
                                    'email-format.index',
                                    'email-format.create',
                                    'email-format.view',
                                    'email-format.update',
                                    'email-format.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'name' =>'Pengurusan SLA',
                        'name_en' =>'SLA management',
                        'permission' => [],
                        'svg_path' => '<rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                        'lower_sub_module' => [
                            [
                                'name' => 'Tetapan Templat',
                                'name_en' =>'Template Settings',
                                'permission' =>[
                                    'sla-template.index',
                                    'sla-template.create',
                                    'sla-template.view',
                                    'sla-template.update',
                                    'sla-template.delete',
                                ]
                            ],
                            [
                                'name' => 'Tetapan SLA',
                                'name_en' =>'SLA Settings',
                                'permission' =>[
                                    'sla.index',
                                    'sla.create',
                                    'sla.view',
                                    'sla.update',
                                    'sla.delete',
                                ]
                            ],
                        ]
                    ],

                    [
                        'name' =>'Konfigurasi Sistem',
                        'name_en' =>'System Configuration',
                        'permission' => [],
                        'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />',
                        'lower_sub_module' => [
                            [
                                'name' => 'Tetapan Global',
                                'name_en' =>'Global Settings',
                                'permission' =>[
                                    'global-setting.index',
                                    'global-setting.create',
                                    'global-setting.view',
                                    'global-setting.update',
                                    'global-setting.delete',
                                ]
                            ],
                            [
                                'name' => 'Kod Tindakan',
                                'name_en' =>'Action Codes',
                                'permission' =>[
                                    'action-code.index',
                                    'action-code.create',
                                    'action-code.view',
                                    'action-code.update',
                                    'action-code.delete',
                                ]
                            ],
                            [
                                'name' => 'Modul',
                                'name_en' =>'Modules',
                                'permission' =>[
                                    'module.index',
                                    // 'module.create',
                                    'module.view',
                                    'module.update',
                                    // 'module.delete',
                                ]
                            ],
                        ]
                    ],
                ]
            ],

            [
                'module' => 'Pengurusan Insiden',
                'name_en' =>'Incident Management',
                'permission' => [],
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />',
                'sub_module' => [
                    [
                        'name' =>'Insiden',
                        'name_en' =>'Incidents',
                        'permission' =>[
                            'incident.index.all',
                            'incident.index.self',
                            'incident.create',
                            'incident.view',
                            'incident.update',
                            // 'incident.delete',
                        ]
                    ],
                ]
            ],

            [
                'module' => 'Jejak Audit',
                'name_en' =>'Audit Trails',
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />',
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
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />',
                'name_en' =>'Reports',
                'permission' =>[
                    'report.index',
                    'report.generate',
                    // -- more from report --
                ]
            ],

            [
                'module' => 'Knowledge Base',
                'name_en' =>'Knowledge Base',
                'permission' => [],
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />',
                'sub_module' => [
                    [
                        'name' =>'Knowledge Entries',
                        'name_en' =>'Knowledge Entries',
                        'permission' =>[
                            'knowledge-base.index',
                            'knowledge-base.create',
                            'knowledge-base.view',
                            'knowledge-base.update',
                            'knowledge-base.delete',
                        ]
                    ],
                ]
            ],

            [
                'module' => 'Notifikasi',
                'name_en' =>'Notifications',
                'permission' => [],
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />',
                'sub_module' => [
                    [
                        'name' =>'Emel',
                        'name_en' =>'Email',
                        'permission' =>[
                            'email-notification.index',
                            'email-notification.view',
                            'email-notification.receive'
                        ]
                    ],
                ]
            ],

        ];

        foreach($modules as $module){

            $data_module['name'] = $module['module'];
            $data_module['name_en'] = $module['name_en'];
            $data_module['description'] = $faker->realText(100);
            $data_module['svg_path'] =  isset($module['svg_path']) ? $module['svg_path'] : null;
            $data_module['created_by'] = 1;
            $data_module['updated_by'] =  2;

            $create = Module::create($data_module);

            if(isset($module['permission'])) $this->createPermission($create->id,$module['permission']);

            if(isset($module['sub_module'])){
                foreach($module['sub_module'] as $sub_module){

                    $data_sub_module['name'] = $sub_module['name'];
                    $data_sub_module['name_en'] = $sub_module['name_en'];
                    $data_sub_module['module_id'] = $create->id;
                    $data_sub_module['description'] = $faker->realText(100);
                    $data_sub_module['created_by'] = 1;
                    $data_sub_module['updated_by'] =  2;

                    $create_sub_module = Module::create($data_sub_module);

                    if(isset($sub_module['permission'])) $this->createPermission($create_sub_module->id,$sub_module['permission']);


                    if(isset($sub_module['lower_sub_module'])){
                        foreach($sub_module['lower_sub_module'] as $lower_sub_module){

                            $data_lower_sub_module['name'] = $lower_sub_module['name'];
                            $data_lower_sub_module['name_en'] = $lower_sub_module['name_en'];
                            $data_lower_sub_module['module_id'] = $create_sub_module->id;
                            $data_lower_sub_module['description'] = $faker->realText(100);
                            $data_lower_sub_module['created_by'] = 1;
                            $data_lower_sub_module['updated_by'] =  2;

                            $create_lower_sub_module = Module::create($data_lower_sub_module);

                            if(isset($lower_sub_module['permission'])) $this->createPermission($create_lower_sub_module->id,$lower_sub_module['permission']);

                        }
                    }
                }
            }
        }


    }

    public function createPermission($module_id,$permissions){
        $faker = Faker::create('ms_My');

        foreach($permissions as $permission){
            $data['module_id'] = $module_id;
            $data['name'] = $permission;
            $data['description'] = $faker->realText(100);

            $create_permission = Permission::create($data);
        }
    }
}
