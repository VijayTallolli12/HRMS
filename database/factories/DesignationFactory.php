<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'organization_id' => Organization::factory(),
            'title' => fake()->unique()->randomElement([
                'Manager', 'Senior Engineer', 'Junior Developer', 'Director',
                'VP', 'Associate', 'Analyst', 'Specialist', 'Coordinator',
            ]),
            'level' => fake()->randomElement(['L1', 'L2', 'L3', 'L4', 'L5']),
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
