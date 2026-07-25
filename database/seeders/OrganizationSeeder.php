<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(['name' => 'Default']);
        $admin = User::where('email', 'admin@example.com')->first();

        $org = Organization::create([
            'tenant_id' => $tenant->id,
            'name' => 'Acme Corporation',
            'legal_name' => 'Acme Corporation Ltd.',
            'tax_id' => 'TAX-001',
            'address' => [
                'street' => '123 Business Ave',
                'city' => 'New York',
                'state' => 'NY',
                'country' => 'USA',
                'zip' => '10001',
            ],
            'status' => 'active',
            'created_by' => $admin?->id,
        ]);

        $branches = collect(['Headquarters', 'West Coast Office', 'European Office'])->map(
            fn ($name) => Branch::create([
                'organization_id' => $org->id,
                'name' => $name,
                'address' => ['street' => fake()->streetAddress(), 'city' => fake()->city(), 'country' => 'USA'],
                'phone' => fake()->phoneNumber(),
                'status' => 'active',
                'created_by' => $admin?->id,
            ])
        );

        $deptNames = ['Human Resources', 'Engineering', 'Marketing', 'Finance', 'Operations'];
        $departments = collect($deptNames)->map(
            fn ($name) => Department::create([
                'organization_id' => $org->id,
                'name' => $name,
                'description' => "Department of {$name}",
                'status' => 'active',
                'created_by' => $admin?->id,
            ])
        );

        $designationData = [
            'Human Resources' => ['HR Manager', 'HR Coordinator', 'Recruiter'],
            'Engineering' => ['Senior Engineer', 'Junior Developer', 'Tech Lead'],
            'Marketing' => ['Marketing Manager', 'Content Strategist', 'Designer'],
            'Finance' => ['Financial Analyst', 'Accountant', 'Controller'],
            'Operations' => ['Operations Manager', 'Logistics Coordinator', 'Analyst'],
        ];

        foreach ($departments as $dept) {
            $titles = $designationData[$dept->name] ?? ['Specialist'];
            foreach ($titles as $title) {
                Designation::create([
                    'department_id' => $dept->id,
                    'organization_id' => $org->id,
                    'title' => $title,
                    'level' => 'L'.rand(1, 5),
                    'description' => "Role: {$title}",
                    'status' => 'active',
                    'created_by' => $admin?->id,
                ]);
            }
        }

        for ($i = 0; $i < 10; $i++) {
            $dept = $departments->random();
            $desig = Designation::where('department_id', $dept->id)->first();
            Employee::create([
                'organization_id' => $org->id,
                'branch_id' => $branches->random()->id,
                'department_id' => $dept->id,
                'designation_id' => $desig?->id,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'employee_number' => 'EMP-'.str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'hired_at' => fake()->dateTimeBetween('-3 years', 'now'),
                'status' => 'active',
                'meta' => [],
                'created_by' => $admin?->id,
            ]);
        }
    }
}
