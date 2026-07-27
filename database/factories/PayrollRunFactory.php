<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Organization;
use App\Models\PayrollRun;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollRunFactory extends Factory
{
    protected $model = PayrollRun::class;

    public function definition(): array
    {
        $statuses = ['draft', 'processing', 'completed', 'paid'];
        $status = fake()->randomElement($statuses);
        $start = fake()->dateTimeBetween('-6 months', '-1 month');
        $end = (clone $start)->modify('+1 month -1 day');

        return [
            'organization_id' => Organization::factory(),
            'branch_id' => Branch::factory(),
            'period_start' => $start->format('Y-m-d'),
            'period_end' => $end->format('Y-m-d'),
            'status' => $status,
            'total_gross' => fake()->randomFloat(2, 50000, 500000),
            'total_deductions' => fake()->randomFloat(2, 5000, 100000),
            'total_net' => fake()->randomFloat(2, 40000, 400000),
            'processed_by' => User::factory(),
            'processed_at' => in_array($status, ['completed', 'paid']) ? fake()->dateTimeBetween('-6 months', 'now') : null,
        ];
    }
}
