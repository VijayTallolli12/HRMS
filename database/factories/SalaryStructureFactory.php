<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\SalaryStructure;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryStructureFactory extends Factory
{
    protected $model = SalaryStructure::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'organization_id' => Organization::factory(),
            'basic_salary' => fake()->randomFloat(2, 3000, 15000),
            'currency' => 'INR',
            'pay_frequency' => fake()->randomElement(['monthly', 'bi-weekly', 'weekly']),
            'effective_from' => fake()->dateTimeBetween('-2 years', '-6 months'),
            'effective_to' => null,
            'is_active' => true,
        ];
    }
}
