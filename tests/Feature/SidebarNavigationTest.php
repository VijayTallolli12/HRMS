<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class SidebarNavigationTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
    }

    public function test_super_admin_sees_new_sidebar_information_architecture(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Attendance')
            ->assertSee('Overview')
            ->assertSee('Daily Attendance')
            ->assertSee('Shift Management')
            ->assertSee('Regularisation')
            ->assertSee('Attendance Corrections')
            ->assertSee('Missing Punches')
            ->assertSee('Leaves')
            ->assertSee('Apply Leave')
            ->assertSee('Leave Requests')
            ->assertSee('Leave Balance')
            ->assertSee('Holiday Calendar')
            ->assertSee('Payroll')
            ->assertSee('Salary Processing')
            ->assertSee('Salary Structure')
            ->assertSee('Payslips')
            ->assertSee('Bonuses &amp; Incentives', false)
            ->assertSee('Deductions')
            ->assertSee('Reimbursements')
            ->assertSee('Employees')
            ->assertSee('Employee Directory')
            ->assertSee('Departments')
            ->assertSee('Designations')
            ->assertSee('Settings')
            ->assertSee('Company Settings')
            ->assertSee('Roles &amp; Permissions', false);
    }

    public function test_branch_admin_sidebar_hides_unpermitted_settings_items(): void
    {
        $user = User::factory()->create();
        $user->assignRole('branch-admin');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Attendance')
            ->assertSee('Leaves')
            ->assertSee('Payroll')
            ->assertSee('Employees')
            ->assertDontSee('Company Settings')
            ->assertDontSee('Roles &amp; Permissions', false);
    }

    public function test_sidebar_links_reuse_existing_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $response = $this->actingAs($user)->get(route('dashboard'));

        foreach ([
            route('attendances.dashboard'),
            route('attendances.daily-register'),
            route('attendances.corrections.index'),
            route('missing-punches.index'),
            route('leaves.create'),
            route('leave-balances.index'),
            route('payroll.runs.index'),
            route('payroll.salary-structures.index'),
            route('payroll.salary-components.index'),
            route('employees.index'),
            route('departments.index'),
            route('designations.index'),
            route('settings.index'),
            route('user-management.index'),
        ] as $route) {
            $response->assertSee('href="'.$route, false);
        }
    }
}
