<?php

namespace Database\Factories;

use App\Models\LatePolicy;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class LatePolicyFactory extends Factory
{
    protected $model = LatePolicy::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->unique()->words(2, true).' Policy',
            'grace_minutes' => fake()->numberBetween(0, 15),
            'max_late_per_month' => fake()->numberBetween(1, 5),
            'penalty_type' => fake()->randomElement(['warning', 'deduction', 'suspension']),
            'penalty_amount' => fake()->optional(0.5)->randomFloat(2, 10, 500),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
