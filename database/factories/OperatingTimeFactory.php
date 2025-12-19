<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OperatingTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $start = Carbon::createFromTime(fake()->numberBetween(8, 16),fake()->numberBetween(0, 59));

        $end = (clone $start)->addHours(fake()->numberBetween(1, 5)); 
        
        return [
            'day_start' => fake()->numberBetween(1,3),
            'day_end' => fake()->numberBetween(4,7),
            'duration' => fake()->numberBetween(1,3),
            'operation_start' => $start->format('H:i:s'),
            'operation_end' => $end->format('H:i:s'),
            'is_active' => true
        ];
    }
}
