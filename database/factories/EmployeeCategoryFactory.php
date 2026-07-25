<?php

namespace Database\Factories;

use App\Models\EmployeeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeCategoryFactory extends Factory
{
    protected $model = EmployeeCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Executive', 'Senior Management', 'Middle Management', 'Junior Management', 'Staff', 'Intern',
            ]),
            'description' => fake()->sentence(),
            'level' => fake()->randomElement(['L1', 'L2', 'L3', 'L4', 'L5', 'L6']),
            'is_active' => true,
        ];
    }
}
