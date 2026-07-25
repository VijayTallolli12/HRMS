<?php

namespace Database\Seeders;

use App\Models\LatePolicy;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $org = Organization::first();
        if (! $org) {
            return;
        }

        $policies = [
            ['name' => 'Standard Late Policy', 'grace_minutes' => 10, 'max_late_per_month' => 3, 'penalty_type' => 'warning'],
            ['name' => 'Strict Late Policy', 'grace_minutes' => 5, 'max_late_per_month' => 2, 'penalty_type' => 'deduction', 'penalty_amount' => 100],
            ['name' => 'Flexible Late Policy', 'grace_minutes' => 15, 'max_late_per_month' => 5, 'penalty_type' => 'warning'],
        ];

        foreach ($policies as $policy) {
            LatePolicy::firstOrCreate(
                ['organization_id' => $org->id, 'name' => $policy['name']],
                array_merge($policy, [
                    'organization_id' => $org->id,
                    'description' => 'Default attendance policy',
                    'is_active' => true,
                ])
            );
        }
    }
}
