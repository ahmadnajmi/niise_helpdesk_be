<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SlaTemplate;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Group;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sla>
 */
class SlaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array{
        // $branch = Branch::pluck('id');
        $branch = Branch::inRandomOrder()->limit(fake()->numberBetween(1,5))->pluck('id');
        $category = Category::whereNotNull('category_id')->inRandomOrder()->first();

        $sla_template = SlaTemplate::inRandomOrder()->first();
        $group = Group::inRandomOrder()->first();

        return [
            'category_id'=> $category?->id,
            'branch_id'=> $branch,
            'sla_template_id'=> $sla_template?->id,
            'group_id'=> $group?->id,
            'is_active' => true
        ];
    }
}
   