<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array{
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_no' => '01' . fake()->numberBetween(0, 9) . fake()->numberBetween(1000000, 9999999),
            'office_phone_no' => '03-' . fake()->numberBetween(20000000, 29999999),
            'address' => fake()->address(), 
            'postcode' => fake()->postcode(), 
            'state_id' => fake()->numberBetween(1, 16)
        ];
    }
}
