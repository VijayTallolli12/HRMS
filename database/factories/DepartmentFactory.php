<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'branch_id' => null,
            'name' => fake()->unique()->randomElement([
                'Human Resources', 'Engineering', 'Marketing', 'Finance',
                'Operations', 'Sales', 'Legal', 'IT', 'Customer Support',
            ]),
            'code' => fake()->unique()->bothify('DEP-###'),
            'department_head_id' => null,
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
