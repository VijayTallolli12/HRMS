<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\WeekendPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeekendPolicyFactory extends Factory
{
    protected $model = WeekendPolicy::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->unique()->randomElement([
                'Standard Weekend', 'Alternate Weekend', 'Flexible Weekend',
            ]),
            'weekend_days' => ['Saturday', 'Sunday'],
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
