<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\SubModule;
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
        DB::table('sub_module')->truncate();
        DB::statement("ALTER SEQUENCE MODULE_ID_SEQ RESTART START WITH 1");
        DB::statement("ALTER SEQUENCE SUB_MODULE_ID_SEQ RESTART START WITH 1");

        $faker = Faker::create('ms_My');

        $modules = [
            [
                'module' => 'Pentadbiran Sistem',
                'sub_module' => [
                    [
                        'name' =>'Pengurusan Orang',
                        'lower_sub_module' => ['Pengurusan Individu (Person)','Pengurusan Kumpulan','Pengurusan Peranan']
                    ],
        
                    [
                        'name' =>'Pengurusan Operasi',
                        'lower_sub_module' => ['Pengurusan Kalendar','Pengurusan Masa Operasi','Pengurusan Kategori','Pengurusan Format Email']
                    ],
        
                    [
                        'name' =>'Pengurusan SLA',
                        'lower_sub_module' => ['Tetapan Templat','Tetapan SLA']
                    ],
        
                    [
                        'name' =>'Konfgurasi sistem',
                        'lower_sub_module' => ['Pratetap data','Kod Tindakan','Modul']
                    ],
                ]
            ],

            [
                'module' => 'Pengurusan Insiden',
                'sub_module' => [
                    [
                    'name' =>'Insiden',
                    ],
                ]
            ],

            [
                'module' => 'Jejak Audit',
               
            ],
            [
                'module' => 'Laporan',
               
            ],

            [
                'module' => 'Knowledgebase',
                'sub_module' => [
                    [
                    'name' =>'Knowledge base',
                    ],
                ]
            ],
        ];

        foreach($modules as $module){

            $data_module['name'] = $module['module'];
            $data_module['description'] = $faker->realText(100);
            
            $create = Module::create($data_module);

            if(isset($module['sub_module'])){
                foreach($module['sub_module'] as $sub_module){

                    $data_sub_module['name'] = $sub_module['name'];
                    $data_sub_module['module_id'] = $create->id;
    
                    $create_sub_module = SubModule::create($data_sub_module);
                }
            }
        }

        
    }
}
