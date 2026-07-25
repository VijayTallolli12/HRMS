<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    protected $model = Leave::class;

    public function definition(): array
    {
        $leaveTypes = ['annual', 'sick', 'personal', 'unpaid', 'maternity', 'paternity'];
        $statuses = ['pending', 'approved', 'rejected', 'cancelled'];
        $leaveType = fake()->randomElement($leaveTypes);
        $status = fake()->randomElement($statuses);
        $startDate = fake()->dateTimeBetween('-60 days', '+30 days');
        $days = fake()->numberBetween(1, 14);
        $endDate = (clone $startDate)->modify('+'.($days - 1).' days');

        return [
            'employee_id' => Employee::factory(),
            'organization_id' => Organization::factory(),
            'leave_type' => $leaveType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $days,
            'reason' => fake()->optional(0.8)->sentence(),
            'status' => $status,
            'rejection_reason' => $status === 'rejected' ? fake()->sentence() : null,
        ];
    }
}
