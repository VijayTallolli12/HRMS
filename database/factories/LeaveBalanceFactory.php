<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveBalanceFactory extends Factory
{
    protected $model = LeaveBalance::class;

    public function definition(): array
    {
        $entitled = fake()->randomFloat(1, 5, 30);
        $taken = fake()->randomFloat(1, 0, $entitled);
        $pending = fake()->randomFloat(1, 0, $entitled - $taken);

        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'year' => (int) date('Y'),
            'entitled' => $entitled,
            'taken' => $taken,
            'pending' => $pending,
            'remaining' => round($entitled - $taken - $pending, 1),
        ];
    }
}
