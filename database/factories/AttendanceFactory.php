<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $statuses = ['present', 'absent', 'late', 'half-day', 'remote'];
        $status = fake()->randomElement($statuses);
        $clockIn = $status === 'absent' ? null : '09:00:00';
        $clockOut = $status === 'absent' ? null : '17:30:00';
        $hoursWorked = $status === 'absent' ? 0 : ($status === 'half-day' ? 4.0 : 8.5);
        $lateMinutes = $status === 'late' ? fake()->numberBetween(5, 60) : 0;

        return [
            'organization_id' => Organization::factory(),
            'employee_id' => Employee::factory(),
            'date' => fake()->dateTimeBetween('-30 days', 'now'),
            'clock_in' => $clockIn,
            'clock_out' => $clockOut,
            'status' => $status,
            'hours_worked' => $hoursWorked,
            'overtime_hours' => fake()->randomFloat(2, 0, 4),
            'late_minutes' => $lateMinutes,
            'early_leave_minutes' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
