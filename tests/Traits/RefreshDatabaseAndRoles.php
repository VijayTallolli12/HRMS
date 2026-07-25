<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

trait RefreshDatabaseAndRoles
{
    use RefreshDatabase;

    protected function seedRolesAndPermissions(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'tenant', 'organization', 'branch', 'department', 'designation', 'employee',
            'employment-type', 'employee-category', 'employment-status',
            'shift', 'shift-assignment', 'work-schedule', 'holiday',
            'weekend-policy', 'reporting-hierarchy', 'cost-center',
            'attendance', 'attendance-adjustment', 'overtime-request', 'late-policy', 'leave',
        ];
        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}-{$module}", 'guard_name' => 'web']);
            }
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        $branchAdmin = Role::firstOrCreate(['name' => 'branch-admin', 'guard_name' => 'web']);
        $branchAdmin->givePermissionTo([
            'view-employee', 'create-employee', 'update-employee',
            'view-branch',
            'view-department', 'view-designation',
            'view-employment-type', 'view-employee-category', 'view-employment-status',
            'view-shift', 'create-shift', 'update-shift',
            'view-shift-assignment', 'create-shift-assignment', 'update-shift-assignment',
            'view-work-schedule',
            'view-holiday', 'create-holiday', 'update-holiday',
            'view-weekend-policy',
            'view-reporting-hierarchy', 'create-reporting-hierarchy', 'update-reporting-hierarchy',
            'view-cost-center',
            'view-attendance', 'create-attendance', 'update-attendance',
            'view-attendance-adjustment', 'create-attendance-adjustment', 'update-attendance-adjustment',
            'view-overtime-request', 'create-overtime-request', 'update-overtime-request',
            'view-late-policy',
            'view-leave', 'create-leave', 'update-leave',
        ]);
    }
}
