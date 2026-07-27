<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayslipFactory extends Factory
{
    protected $model = Payslip::class;

    public function definition(): array
    {
        $basicSalary = fake()->randomFloat(2, 3000, 15000);
        $gross = $basicSalary * fake()->randomFloat(2, 1.2, 1.6);
        $deductions = $gross * fake()->randomFloat(2, 0.15, 0.30);
        $net = $gross - $deductions;
        $statuses = ['pending', 'processed', 'paid'];
        $status = fake()->randomElement($statuses);

        return [
            'payroll_run_id' => PayrollRun::factory(),
            'employee_id' => Employee::factory(),
            'basic_salary' => $basicSalary,
            'gross_earnings' => $gross,
            'total_deductions' => $deductions,
            'net_salary' => $net,
            'status' => $status,
            'paid_at' => $status === 'paid' ? fake()->dateTimeBetween('-3 months', 'now') : null,
        ];
    }
}
