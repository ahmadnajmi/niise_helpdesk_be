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
                'module' => 'Papan Pemuka',
                'name_en' =>'Dashboard',
                'permission' =>['dashboard.index'],
                'svg_path' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',

            ],
            [
                'module' => 'Pentadbiran Sistem',
                'name_en' => 'System Administration',
                'permission' => ['index'],
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'name_en' => 'People Management',
                        'permission' => ['index'],
                        'lower_sub_module' => [ 
                            [
                                'name' => 'Pengurusan Individu (Person)',
                                'name_en' => 'Individual Management (Person)',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Kumpulan',
                                'name_en' => 'Group Management',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Peranan',
                                'name_en' => 'Role Management',
                                'permission' =>['role.index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Pengurusan Operasi',
                        'name_en' =>'Operations Management',
                        'permission' => ['index'],
                        'lower_sub_module' => [
                            [
                                'name' => 'Pengurusan Kalendar',
                                'name_en' =>'Calendar Management',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Masa Operasi',
                                'name_en' =>'Operation Time Management',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Kategori',
                                'name_en' =>'Category Management',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Format Email',
                                'name_en' =>'Email Format Management',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Pengurusan SLA',
                        'name_en' =>'SLA management',
                        'permission' => ['index'],
                        'lower_sub_module' => [
                            [
                                'name' => 'Tetapan Templat',
                                'name_en' =>'Template Settings',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Tetapan SLA',
                                'name_en' =>'SLA settings',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Konfgurasi sistem',
                        'name_en' =>'System configuration',
                        'permission' => ['index'],
                        'lower_sub_module' => [
                            [
                                'name' => 'Pratetap data',
                                'name_en' =>'Data preset',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Kod Tindakan',
                                'name_en' =>'Action Code',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Modul',
                                'name_en' =>'Module',
                                'permission' =>['module.index','create','view','update','delete']
                            ],
                        ]
                    ],
                ]
            ],

            [
                'module' => 'Pengurusan Insiden',
                'name_en' =>'Incident Management',
                'permission' => ['index'],
                'svg_path' => '<path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />',
                'sub_module' => [
                    [
                        'name' =>'Insiden',
                        'name_en' =>'Incident',
                        'permission' =>['incident.index','create','view','update','delete']
                    ],
                ]
            ],

            [
                'module' => 'Jejak Audit',
                'name_en' =>'Audit Trail',
                'permission' =>['index','create','view','update','delete']
            ],
            [
                'module' => 'Laporan',
                'name_en' =>'Report',
                'permission' =>['report.index','view']
            ],

            [
                'module' => 'Knowledgebase',
                'name_en' =>'Knowledgebase',
                'permission' => ['index'],
                'sub_module' => [
                    [
                        'name' =>'Knowledge base',
                        'name_en' =>'Knowledgebase',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],

            [
                'module' => 'Notifikasi',
                'name_en' =>'Notification',
                'permission' => ['index'],
                'sub_module' => [
                    [
                        'name' =>'Notifikasi Aplikasi',
                        'name_en' =>'Application Notification',
                        'permission' =>['index']
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
