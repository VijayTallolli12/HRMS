<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class HolidayFactory extends Factory
{
    protected $model = Holiday::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->randomElement([
                'New Year', 'Independence Day', 'Labor Day', 'Thanksgiving',
                'Christmas', 'Easter', 'Memorial Day', 'Veterans Day',
            ]),
            'date' => fake()->dateTimeThisYear(),
            'type' => fake()->randomElement(['public', 'national', 'religious', 'optional']),
            'description' => fake()->optional(0.5)->sentence(),
            'is_active' => true,
        ];
    }
}
