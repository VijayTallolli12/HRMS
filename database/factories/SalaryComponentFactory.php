<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\SalaryComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryComponentFactory extends Factory
{
    protected $model = SalaryComponent::class;

    public function definition(): array
    {
        $components = [
            ['name' => 'Basic Salary', 'code' => 'BASIC', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 5000],
            ['name' => 'House Rent Allowance', 'code' => 'HRA', 'type' => 'earning', 'calculation_type' => 'percentage', 'default_value' => 40],
            ['name' => 'Dearness Allowance', 'code' => 'DA', 'type' => 'earning', 'calculation_type' => 'percentage', 'default_value' => 10],
            ['name' => 'Transport Allowance', 'code' => 'TA', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 800],
            ['name' => 'Medical Allowance', 'code' => 'MED', 'type' => 'earning', 'calculation_type' => 'fixed', 'default_value' => 500],
            ['name' => 'Provident Fund', 'code' => 'PF', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_value' => 12],
            ['name' => 'ESI', 'code' => 'ESI', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_value' => 0.75],
            ['name' => 'Professional Tax', 'code' => 'PTAX', 'type' => 'deduction', 'calculation_type' => 'fixed', 'default_value' => 200],
            ['name' => 'Income Tax', 'code' => 'ITAX', 'type' => 'deduction', 'calculation_type' => 'percentage', 'default_value' => 10],
        ];
        $component = fake()->randomElement($components);

        return [
            'organization_id' => Organization::factory(),
            'name' => $component['name'],
            'code' => $component['code'],
            'type' => $component['type'],
            'calculation_type' => $component['calculation_type'],
            'default_value' => $component['default_value'],
            'is_active' => true,
        ];
    }
}
