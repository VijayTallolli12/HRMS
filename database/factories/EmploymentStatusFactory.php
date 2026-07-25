<?php

namespace Database\Factories;

use App\Models\EmploymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentStatusFactory extends Factory
{
    protected $model = EmploymentStatus::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Active', 'On Leave', 'Probation', 'Suspended', 'Terminated', 'Retired',
            ]),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
