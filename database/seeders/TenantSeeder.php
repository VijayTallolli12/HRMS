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
        $tenant = Tenant::firstOrCreate([
            'name' => 'Default',
        ], [
            'domain' => null,
            'meta' => [],
        ]);

        $user = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('password'),
            'tenant_id' => $tenant->id,
        ]);

        // Assign admin role later when roles exist.
    }
}
