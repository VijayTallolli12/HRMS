<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['name' => 'Default'],
            [
                'domain' => 'default.hrms.test',
                'meta' => [],
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($admin, 'assignRole') && ! $admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }

        $branchAdmin = User::firstOrCreate(
            ['email' => 'branch@example.com'],
            [
                'name' => 'Branch Administrator',
                'password' => Hash::make('password'),
                'tenant_id' => $tenant->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        if (method_exists($branchAdmin, 'assignRole') && ! $branchAdmin->hasRole('branch-admin')) {
            $branchAdmin->assignRole('branch-admin');
        }
    }
}
