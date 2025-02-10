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
                    ],
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
                    ],
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
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
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
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
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
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
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
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
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
                    [
                        'module' => 'Papan Pemuka',
                        'permission' =>['index']
                    ],
                ]
            ],
        ];


        foreach($roles as $role){

            $data_role['name'] = $role['name'];
            $data_role['description'] = $faker->realText(100);
            
            $create = Role::create($data_role);

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

        // if(!$get_permission)dd($module_id,$access_permission);

        $data_role_permission['permission_id'] = $get_permission->id;

        $check_role_permission = RolePermission::where('permission_id',$data_role_permission['permission_id'])->where('role_id',$data_role_permission['role_id'])->exists();

        if(!$check_role_permission){
            $create_permission = RolePermission::create($data_role_permission);
        }


    }
}
