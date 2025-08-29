<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\CompanyContract;
use App\Http\Services\SlaTemplateServices;
use App\Models\SlaTemplate;

class SlaTemplateFactory extends Factory
{
    public function definition(): array{
        $company = Company::inRandomOrder()->first();

        $contract = CompanyContract::where('company_id',$company?->id)->inRandomOrder()->first();

        return [
            'severity_id' => fake()->numberBetween(1,5),
            'service_level' => fake()->numberBetween(30,100).'%',
            'company_id' => $company?->id,
            'company_contract_id' => $contract?->id,
            'response_time'=> fake()->numberBetween(1,60),
            'response_time_type'=> fake()->numberBetween(1,3),
            'response_time_penalty'=> fake()->numberBetween(50,100),
            'resolution_time'=> fake()->numberBetween(10,60),
            'resolution_time_type'=> fake()->numberBetween(1,3),
            'resolution_time_penalty'=> fake()->numberBetween(100,1000),
            'response_time_location'=> fake()->numberBetween(10,60),
            'response_time_location_type'=> fake()->numberBetween(1,3),
            'response_time_location_penalty'=> fake()->numberBetween(100,1000),
            'temporary_resolution_time'=> fake()->numberBetween(10,60),
            'temporary_resolution_time_type'=> fake()->numberBetween(1,3),
            'temporary_resolution_time_penalty'=> fake()->numberBetween(100,1000),
            'dispatch_time'=> fake()->numberBetween(1,5),
            'dispatch_time_type'=> fake()->numberBetween(1,5),
            'notes' => fake()->sentence(15),
        ];
    }

    public function configure() {
        return $this->afterCreating(function (SlaTemplate $slaTemplate) {
            SlaTemplateServices::generateVersion($slaTemplate);
        });
    }
}
