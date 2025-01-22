<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->truncate();
        DB::table('permissions')->truncate();
        DB::statement("ALTER SEQUENCE ROLE_ID_SEQ RESTART START WITH 1");
        DB::statement("ALTER SEQUENCE PERMISSIONS_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $roles = [
            [
                'name' => 'Kakitangan JIM yang berkelayakan (Pengadu)',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                ]
            ],
            [
                'name' => 'NOC /SOC / AOC (2nd Lvl)',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                ]
            ],
            [
                'name' => 'Jurutera HDS ICT Frontliner',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                ]
            ],
            [
                'name' => 'Penyelia Helpdesk ICT',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                ]
            ],
            [
                'name' => 'Pentadbir Helpdesk Sistem (BTMR)',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  1,
                        'allowed_create' => 1,
                        'allowed_view' =>  1,
                        'allowed_update' => 1,
                        'allowed_delete' => 1,
                    ],
                ]
            ],
            [
                'name' => 'Kontraktor',
                'permission' => [
                    [
                        'sub_module' => 'Pengurusan Individu (Person)',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kumpulan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Peranan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kalendar',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Masa Operasi',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Kategori',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pengurusan Format Email',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan Templat',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Tetapan SLA',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Pratetap data',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Kod Tindakan',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
                    ],
                    [
                        'sub_module' => 'Modul',
                        'allowed_list' =>  0,
                        'allowed_create' => 0,
                        'allowed_view' =>  0,
                        'allowed_update' => 0,
                        'allowed_delete' => 0,
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

                    $data_permission['sub_module_id'] = 1;
                    $data_permission['role_id'] = $create->id;
                    $data_permission['allowed_list'] = $permission['allowed_list'];
                    $data_permission['allowed_create'] = $permission['allowed_create'];
                    $data_permission['allowed_view'] = $permission['allowed_view'];
                    $data_permission['allowed_update'] = $permission['allowed_update'];
                    $data_permission['allowed_delete'] = $permission['allowed_delete'];
                    
                    $create_permission = Permission::create($data_permission);
                }
            }
        }
    }
}
