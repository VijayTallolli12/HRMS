<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftAssignmentFactory extends Factory
{
    protected $model = ShiftAssignment::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'shift_id' => Shift::factory(),
            'effective_from' => fake()->dateTimeBetween('-1 month', 'now'),
            'effective_to' => fake()->optional(0.3)->dateTimeBetween('+1 month', '+6 months'),
            'notes' => fake()->optional(0.5)->sentence(),
            'is_active' => true,
        ];
    }
}
