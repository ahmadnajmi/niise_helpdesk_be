<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\UsersImport;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Company;
use App\Models\CompanyContract;

class UserProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        UserRole::truncate();
        Company::truncate();

        if (DB::getDriverName() === 'oracle') {
            DB::statement("ALTER SEQUENCE COMPANIES_ID_SEQ RESTART START WITH 1");
            DB::statement("ALTER SEQUENCE COMPANY_CONTRACTS_ID_SEQ RESTART START WITH 1");
        }
        

        $data_company['name'] = 'HEITECH PADU BERHAD';
        $data_company['nickname'] = 'HEITECH';
        $data_company['address'] = 'SUBANG JAYA';
        $data_company['postcode'] = 47600;
        $data_company['city'] = 'SUBANG JAYA';

        $create = Company::create($data_company);

        $data_company_contract['company_id'] = $create->id;
        $data_company_contract['contract_no'] = 'NIIse';
        $data_company_contract['name'] = 'NIIse';

        CompanyContract::create($data_company_contract);


        Excel::import(new UsersImport, 'database/seeders/excel/user_prod.xlsx');
    }
}
