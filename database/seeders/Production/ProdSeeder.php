<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder; 
use Database\Seeders\ModuleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RefTableSeeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\EmailTemplateSeeder;
use Database\Seeders\ActionCodeSeeder;
use Database\Seeders\ReportSeeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ModuleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RefTableSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(UserProdSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(ActionCodeSeeder::class);
        $this->call(ReportSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(SlaSeeder::class);
        
    }
}
