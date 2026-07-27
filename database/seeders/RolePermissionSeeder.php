<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // V1 Permission Modules
        // Modules where Branch Admin gets full CRUD: employee, attendance, leave
        // Modules where Branch Admin gets view-only: everything else
        // No module gets delete for Branch Admin in V1 (super admin only)
        $modules = [
            'tenant', 'organization', 'branch', 'department', 'designation', 'employee',
            'employment-type', 'employee-category', 'employment-status',
            'shift', 'shift-assignment', 'work-schedule', 'holiday',
            'weekend-policy', 'reporting-hierarchy', 'cost-center',
            'attendance', 'attendance-adjustment', 'overtime-request', 'late-policy', 'leave',
            'leave-type', 'leave-balance',
            'user-management', 'settings',
            'salary-component', 'salary-structure', 'payroll-run', 'payslip',
        ];
        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "{$action}-{$module}", 'guard_name' => 'web']);
            }
        }

        // Super Admin: full access to all modules
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Branch Admin: scoped to their branch_id
        // Manage employee master data and daily HR operations within branch; no delete in V1.
        // No access to: tenant, organization management
        // No delete permissions in V1
        $branchAdmin = Role::firstOrCreate(['name' => 'branch-admin', 'guard_name' => 'web']);
        $branchAdmin->givePermissionTo([
            // Employee management (full CRUD within branch, no delete)
            'view-employee', 'create-employee', 'update-employee',

            // Attendance management (full CRUD within branch, no delete)
            'view-attendance', 'create-attendance', 'update-attendance',
            'view-attendance-adjustment', 'create-attendance-adjustment', 'update-attendance-adjustment',

            // Leave management (full CRUD within branch, no delete)
            'view-leave', 'create-leave', 'update-leave',

            // Overtime requests (within branch)
            'view-overtime-request', 'create-overtime-request', 'update-overtime-request',

            // View-only reference data (needed for forms/dropdowns)
            'view-branch',
            'view-department', 'create-department', 'update-department',
            'view-designation', 'create-designation', 'update-designation',
            'view-employment-type',
            'view-employee-category',
            'view-employment-status',
            'view-shift',
            'view-shift-assignment',
            'view-work-schedule',
            'view-holiday',
            'view-weekend-policy',
            'view-reporting-hierarchy',
            'view-cost-center',
            'view-late-policy',

            // Leave type and balance (view-only)
            'view-leave-type',
            'view-leave-balance',

            // Payroll components (view-only)
            'view-salary-component',
            'view-salary-structure',
            'view-payroll-run',
            'view-payslip',
        ]);
    }
}
