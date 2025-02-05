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
        DB::table('module')->truncate();
        DB::statement("ALTER SEQUENCE MODULE_ID_SEQ RESTART START WITH 1");
        DB::table('permissions')->truncate();
        DB::statement("ALTER SEQUENCE PERMISSIONS_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $modules = [
            [
                'module' => 'Pentadbiran Sistem',
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'lower_sub_module' => [ 
                            [
                                'name' => 'Pengurusan Individu (Person)',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Kumpulan',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Peranan',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Pengurusan Operasi',
                        'lower_sub_module' => [
                            [
                                'name' => 'Pengurusan Kalendar',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Masa Operasi',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Kategori',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Pengurusan Format Email',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Pengurusan SLA',
                        'lower_sub_module' => [
                            [
                                'name' => 'Tetapan Templat',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Tetapan SLA',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
        
                    [
                        'name' =>'Konfgurasi sistem',
                        'lower_sub_module' => [
                            [
                                'name' => 'Pratetap data',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Kod Tindakan',
                                'permission' =>['index','create','view','update','delete']
                            ],
                            [
                                'name' => 'Modul',
                                'permission' =>['index','create','view','update','delete']
                            ],
                        ]
                    ],
                ]
            ],

            [
                'module' => 'Pengurusan Insiden',
                'sub_module' => [
                    [
                        'name' =>'Insiden',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],

            [
                'module' => 'Jejak Audit',
                'permission' =>['index','create','view','update','delete']
            ],
            [
                'module' => 'Laporan',
                'permission' =>['index','view']
            ],

            [
                'module' => 'Knowledgebase',
                'sub_module' => [
                    [
                        'name' =>'Knowledge base',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],
        ];

        foreach($modules as $module){

            $data_module['name'] = $module['module'];
            $data_module['description'] = $faker->realText(100);
            
            $create = Module::create($data_module);

            if(isset($module['permission'])) $this->createPermission($create->id,$module['permission']);

            if(isset($module['sub_module'])){
                foreach($module['sub_module'] as $sub_module){

                    $data_sub_module['name'] = $sub_module['name'];
                    $data_sub_module['module_id'] = $create->id;
                    $data_sub_module['description'] = $faker->realText(100);

                    $create_sub_module = Module::create($data_sub_module);

                    if(isset($sub_module['permission'])) $this->createPermission($create_sub_module->id,$sub_module['permission']);


                    if(isset($sub_module['lower_sub_module'])){
                        foreach($sub_module['lower_sub_module'] as $lower_sub_module){
        
                            $data_lower_sub_module['name'] = $lower_sub_module['name'];
                            $data_lower_sub_module['module_id'] = $create_sub_module->id;
                            $data_lower_sub_module['description'] = $faker->realText(100);
        
                            $create_lower_sub_module = Module::create($data_lower_sub_module);

                            if(isset($lower_sub_module['permission'])) $this->createPermission($create_lower_sub_module->id,$lower_sub_module['permission']);

                        }
                    }
                }
            }
        }

        
    }

    public function createPermission($module_id,$permissions){

        foreach($permissions as $permission){
            $data['module_id'] = $module_id;
            $data['name'] = $permission;

            $create_permission = Permission::create($data);
        }
    }
}
