<?php

namespace Database\Factories;

use App\Models\EmploymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentTypeFactory extends Factory
{
    protected $model = EmploymentType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Full-time', 'Part-time', 'Contract', 'Intern', 'Temporary', 'Freelance', 'Consultant',
            ]),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
