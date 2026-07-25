<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'branch_id' => null,
            'department_id' => null,
            'designation_id' => null,
            'employment_type_id' => null,
            'employee_category_id' => null,
            'employment_status_id' => null,
            'cost_center_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'employee_number' => fake()->unique()->numerify('EMP-#####'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'hired_at' => fake()->dateTimeBetween('-5 years', 'now'),
            'status' => 'active',
            'meta' => [],
        ];
    }

    public function forOrganization(Organization $org): static
    {
        return $this->state(['organization_id' => $org->id]);
    }

    public function forBranch(Branch $branch): static
    {
        return $this->state([
            'branch_id' => $branch->id,
            'organization_id' => $branch->organization_id,
        ]);
    }
}
