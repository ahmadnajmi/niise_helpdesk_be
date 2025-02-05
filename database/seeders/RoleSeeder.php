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

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->truncate();
        DB::table('role_permissions')->truncate();
        DB::statement("ALTER SEQUENCE ROLE_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $roles = [
            [
                'name' => 'Kakitangan JIM yang berkelayakan (Pengadu)',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' => ['index','create','view']
                    ]
                ]
            ],
            [
                'name' => 'NOC /SOC / AOC (2nd Lvl)',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>['index','view','update']
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>['index','view']
                    ],
                    [
                        'module' => 'Knowledge base',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],
            [
                'name' => 'Jurutera HDS ICT Frontliner',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>['index','create','view','update']
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>['index','view']
                    ],
                    [
                        'module' => 'Knowledge base',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],
            [
                'name' => 'Penyelia Helpdesk ICT',
                'permission' =>  [
                    [
                        'module' => 'Pengurusan Individu (Person)',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kumpulan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Peranan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kalendar',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Masa Operasi',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kategori',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Format Email',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Tetapan Templat',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Tetapan SLA',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pratetap data',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Kod Tindakan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Modul',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' =>'Insiden',
                        'permission' =>['index','create','view','update','delete']
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
                        'module' =>'Knowledge base',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],
            [
                'name' => 'Pentadbir Helpdesk Sistem (BTMR)',
                'permission' =>  [
                    [
                        'module' => 'Pengurusan Individu (Person)',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kumpulan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Peranan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kalendar',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Masa Operasi',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Kategori',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pengurusan Format Email',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Tetapan Templat',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Tetapan SLA',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Pratetap data',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Kod Tindakan',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' => 'Modul',
                        'permission' =>['index','create','view','update','delete']
                    ],
                    [
                        'module' =>'Insiden',
                        'permission' =>['index','create','view','update','delete']
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
                        'module' =>'Knowledge base',
                        'permission' =>['index','create','view','update','delete']
                    ],
                ]
            ],
            [
                'name' => 'Kontraktor',
                'permission' =>  [
                    [
                        'module' => 'Insiden',
                        'permission' =>['index','view','update']
                    ],
                    [
                        'module' => 'Jejak Audit',
                        'permission' =>['index','view']
                    ],
                    [
                        'module' => 'Laporan',
                        'permission' =>['index','view']
                    ],
                ]
            ],
        ];


        foreach($roles as $role){

            $data_role['name'] = $role['name'];
            $data_role['description'] = $faker->realText(100);
            
            $create = Role::create($data_role);

            if(isset($role['permission'])){
                foreach($role['permission'] as $permission){

                    $module = Module::where('name',$permission['module'])->first();

                    $data_role_permission['role_id'] = $create->id;

                    foreach($permission['permission'] as $access_permission){
                        $get_permission = Permission::where('module_id',$module->id)->where('name',$access_permission)->first();

                        if(!$get_permission)dd($get_permission,$module->name,$create->name,$access_permission);

                        $data_role_permission['permission_id'] = $get_permission->id;

                        $create_permission = RolePermission::create($data_role_permission);

                    }






                }
            }
        }
    }
}
