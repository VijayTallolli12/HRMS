<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\ReportingHierarchy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportingHierarchyFactory extends Factory
{
    protected $model = ReportingHierarchy::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'manager_id' => Employee::factory(),
            'reporting_type' => fake()->randomElement(['direct', 'functional', 'administrative']),
            'effective_from' => fake()->dateTimeBetween('-1 year', 'now'),
            'effective_to' => fake()->optional(0.3)->dateTimeBetween('+1 month', '+1 year'),
            'is_active' => true,
        ];
    }
}
