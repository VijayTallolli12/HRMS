<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeSalaryComponent;
use App\Models\SalaryComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeSalaryComponentFactory extends Factory
{
    protected $model = EmployeeSalaryComponent::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'salary_component_id' => SalaryComponent::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'percentage' => fake()->optional(0.5)->randomFloat(2, 5, 40),
            'is_active' => true,
        ];
    }
}
