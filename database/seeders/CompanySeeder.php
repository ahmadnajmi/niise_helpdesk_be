<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Branch;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('COMPANIES')->truncate();

        DB::statement("ALTER SEQUENCE COMPANIES_ID_SEQ RESTART START WITH 1");
        $faker = Faker::create('ms_My');

        $companies = [
            ["name" => "Aexis Technologies Sdn. Bhd.", "nickname" => "Aexis"],
            ["name" => "AQTAR Resources Sdn. Bhd.", "nickname" => "AQTAR"],
            ["name" => "CET Development Sdn Bhd", "nickname" => "CET"],
            ["name" => "Compute IT Sdn. Bhd.", "nickname" => "ComputeIT"],
            ["name" => "Crealogy Sdn. Bhd.", "nickname" => "Crealogy"],
            ["name" => "Creative World Industries Sdn Bhd", "nickname" => "CreativeWorld"],
            ["name" => "Datasonic Technologies Sdn Bhd", "nickname" => "Datasonic"],
            ["name" => "EdgeTech Engineering", "nickname" => "EdgeTech"],
            ["name" => "GITN Sdn Bhd", "nickname" => "GITN"],
            ["name" => "Global Elite Ventures Sdn Bhd", "nickname" => "GlobalElite"],
            ["name" => "HeiTech Padu Berhad (MyIMMs)", "nickname" => "HeiTech"],
            ["name" => "Iburuj Network Sdn Bhd", "nickname" => "Iburuj"],
            ["name" => "Igen Technology (M) Sdn. Bhd", "nickname" => "IgenTech"],
            ["name" => "Infomina Berhad - DC", "nickname" => "Infomina"],
            ["name" => "Intelligence PC Centre", "nickname" => "IPC"],
            ["name" => "Maintenance Building", "nickname" => "Maintenance"],
            ["name" => "Mecacom Technologies Sdn Bhd", "nickname" => "Mecacom"],
            ["name" => "Percetakan Keselamatan Nasional Sdn Bhd", "nickname" => "PKN"],
            ["name" => "Rites Sdn. Bhd.", "nickname" => "Rites"],
            ["name" => "SNS Network (M) Sdn. Bhd.", "nickname" => "SNS"],
            ["name" => "Thames Technology Sdn. Bhd.", "nickname" => "ThamesTech"],
            ["name" => "Tridimas Sdn. Bhd.", "nickname" => "Tridimas"],
            ["name" => "Vista Kencana Sdn Bhd", "nickname" => "VistaKencana"]
        ];

        foreach($companies as $company){

            $get_branch = Branch::select('id')->inRandomOrder()->first();

            $data['name']  = $company['name'];
            $data['nickname']  = $company['nickname'];
            $data['email'] =  $faker->unique()->safeEmail;
            $data['phone_no'] =   $faker->phoneNumber;
            $data['address'] =    $faker->streetAddress;
            $data['postcode'] =  $faker->postcode;
            $data['city'] =   $faker->city;
            $data['state_id'] =  rand(1, 14);
            $data['fax_no'] =   $faker->phoneNumber;

            $create = Company::create($data);
        }

      
    }
}
