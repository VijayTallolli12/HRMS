<?php

namespace Database\Factories;

use App\Models\Payslip;
use App\Models\PayslipItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayslipItemFactory extends Factory
{
    protected $model = PayslipItem::class;

    public function definition(): array
    {
        $componentNames = ['Basic Salary', 'HRA', 'DA', 'TA', 'Medical Allowance', 'PF', 'ESI', 'Professional Tax', 'Income Tax'];
        $types = ['earning', 'deduction'];
        $name = fake()->randomElement($componentNames);

        return [
            'payslip_id' => Payslip::factory(),
            'salary_component_id' => null,
            'component_name' => $name,
            'type' => in_array($name, ['PF', 'ESI', 'Professional Tax', 'Income Tax']) ? 'deduction' : 'earning',
            'amount' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}
