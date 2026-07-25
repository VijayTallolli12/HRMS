<?php

namespace Database\Seeders;

use App\Models\EmployeeCategory;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use Illuminate\Database\Seeder;

class WorkforceFoundationSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Full-time', 'Part-time', 'Contract', 'Intern', 'Temporary', 'Freelance', 'Consultant'];
        foreach ($types as $type) {
            EmploymentType::firstOrCreate(['name' => $type]);
        }

        $categories = [
            ['name' => 'Executive', 'level' => 'L1'],
            ['name' => 'Senior Management', 'level' => 'L2'],
            ['name' => 'Middle Management', 'level' => 'L3'],
            ['name' => 'Junior Management', 'level' => 'L4'],
            ['name' => 'Staff', 'level' => 'L5'],
            ['name' => 'Intern', 'level' => 'L6'],
        ];
        foreach ($categories as $cat) {
            EmployeeCategory::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $statuses = ['Active', 'On Leave', 'Probation', 'Suspended', 'Terminated', 'Retired'];
        foreach ($statuses as $status) {
            EmploymentStatus::firstOrCreate(['name' => $status]);
        }
    }
}
