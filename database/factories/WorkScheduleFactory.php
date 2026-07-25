<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\WorkSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkScheduleFactory extends Factory
{
    protected $model = WorkSchedule::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->unique()->randomElement([
                'Standard Work Week', 'Flex Schedule', 'Compressed Work Week', 'Alternative Schedule',
            ]),
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'hours_per_day' => 8.00,
            'break_minutes' => 60,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
