<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\OvertimeRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OvertimeRequestFactory extends Factory
{
    protected $model = OvertimeRequest::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'employee_id' => Employee::factory(),
            'date' => fake()->dateTimeBetween('-30 days', 'now'),
            'hours' => fake()->randomFloat(2, 1, 8),
            'reason' => fake()->sentence(),
            'status' => 'pending',
            'requested_by' => User::factory(),
        ];
    }
}
