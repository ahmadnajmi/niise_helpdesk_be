<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Models\User;
use App\Models\UserRole;
use Maatwebsite\Excel\Facades\Excel;

class UserProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        UserRole::truncate();

        Excel::import(new UsersImport, 'database/seeders/excel/user_prod.xlsx');
    }
}
