<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        $shifts = [
            ['start_time' => '06:00:00', 'end_time' => '14:00:00'],
            ['start_time' => '14:00:00', 'end_time' => '22:00:00'],
            ['start_time' => '22:00:00', 'end_time' => '06:00:00'],
            ['start_time' => '09:00:00', 'end_time' => '18:00:00'],
        ];
        $shift = fake()->randomElement($shifts);

        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->unique()->word().' Shift',
            'start_time' => $shift['start_time'],
            'end_time' => $shift['end_time'],
            'break_minutes' => 60,
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
