<?php

namespace Database\Factories;

use App\Models\CostCenter;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCenterFactory extends Factory
{
    protected $model = CostCenter::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'code' => fake()->unique()->numerify('CC-###'),
            'name' => fake()->randomElement([
                'Engineering', 'Human Resources', 'Marketing', 'Finance',
                'Operations', 'Sales', 'Legal', 'IT',
            ]),
            'department_id' => null,
            'description' => fake()->sentence(),
            'budget' => fake()->optional(0.7)->randomFloat(2, 10000, 500000),
            'is_active' => true,
        ];
    }
}
