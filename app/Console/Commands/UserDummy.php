<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\UsersImport;
use App\Imports\BranchImport;
use App\Models\IdentityManagement\User;
use App\Models\IdentityManagement\Branch;


use Maatwebsite\Excel\Facades\Excel;



class UserDummy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-dummy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // User::truncate();
        // Branch::truncate();

        Excel::import(new BranchImport, storage_path('app/private/branch_niise.xlsx'));

        Excel::import(new UsersImport, storage_path('app/private/user_niise.xlsx'));
    }
}
