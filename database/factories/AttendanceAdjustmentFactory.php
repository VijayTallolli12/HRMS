<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceAdjustmentFactory extends Factory
{
    protected $model = AttendanceAdjustment::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'attendance_id' => Attendance::factory(),
            'employee_id' => Employee::factory(),
            'reason' => fake()->sentence(),
            'new_clock_in' => '08:30:00',
            'new_clock_out' => '17:30:00',
            'new_status' => 'present',
            'status' => 'pending',
            'requested_by' => User::factory(),
        ];
    }
}
