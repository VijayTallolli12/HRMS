<?php

namespace Database\Factories;

use App\Models\LeaveType;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->randomElement([
                'Annual Leave', 'Sick Leave', 'Personal Leave', 'Unpaid Leave',
                'Maternity Leave', 'Paternity Leave', 'Bereavement Leave', 'Study Leave',
            ]),
            'days_per_year' => fake()->numberBetween(5, 30),
            'is_active' => true,
        ];
    }
}
