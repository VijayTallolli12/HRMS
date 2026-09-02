<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class AttendanceDashboardTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected Tenant $tenant;
    protected Organization $organization;
    protected Branch $branch1;
    protected Branch $branch2;
    protected User $superAdmin;
    protected User $branchAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();

        $this->tenant = Tenant::factory()->create();
        $this->organization = Organization::factory()->create(['tenant_id' => $this->tenant->id]);
        $this->branch1 = Branch::factory()->create(['organization_id' => $this->organization->id]);
        $this->branch2 = Branch::factory()->create(['organization_id' => $this->organization->id]);

        $this->superAdmin = User::factory()->forTenant($this->tenant)->create();
        $this->superAdmin->assignRole('super-admin');

        $this->branchAdmin = User::factory()->forTenant($this->tenant)->create(['branch_id' => $this->branch1->id]);
        $this->branchAdmin->assignRole('branch-admin');
    }

    public function test_unauthenticated_user_cannot_view_attendance_dashboard(): void
    {
        $this->get(route('attendances.dashboard'))->assertRedirect('/login');
    }

    public function test_attendance_dashboard_renders_empty_state_when_no_records_exist(): void
    {
        Employee::factory()->count(5)->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch1->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('attendances.dashboard'));
        $response->assertOk();
        $response->assertSee('No attendance data yet');
        $response->assertSee('Go to Daily Attendance');
        $response->assertViewHas('totalEmployees', 5);
        $response->assertViewHas('presentToday', 0);
        $response->assertViewHas('absentToday', 5);
        $response->assertViewHas('lateToday', 0);
        $response->assertViewHas('attendanceRate', 0);
    }

    public function test_attendance_dashboard_calculates_present_late_absent_and_trend_correctly(): void
    {
        $today = now()->toDateString();

        $emp1 = Employee::factory()->create(['organization_id' => $this->organization->id, 'branch_id' => $this->branch1->id, 'status' => 'active']);
        $emp2 = Employee::factory()->create(['organization_id' => $this->organization->id, 'branch_id' => $this->branch1->id, 'status' => 'active']);
        $emp3 = Employee::factory()->create(['organization_id' => $this->organization->id, 'branch_id' => $this->branch1->id, 'status' => 'active']);

        Attendance::factory()->create([
            'organization_id' => $this->organization->id,
            'employee_id' => $emp1->id,
            'date' => $today,
            'status' => 'present',
            'late_minutes' => 0,
        ]);

        Attendance::factory()->create([
            'organization_id' => $this->organization->id,
            'employee_id' => $emp2->id,
            'date' => $today,
            'status' => 'late',
            'late_minutes' => 20,
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('attendances.dashboard'));
        $response->assertOk();
        $response->assertViewHas('totalEmployees', 3);
        $response->assertViewHas('presentToday', 2);
        $response->assertViewHas('lateToday', 1);
        $response->assertViewHas('absentToday', 1);
        $response->assertViewHas('attendanceRate', 67);

        $trend = $response->viewData('attendanceTrend');
        $this->assertIsArray($trend);
        $this->assertArrayHasKey('labels', $trend);
        $this->assertArrayHasKey('present', $trend);
        $this->assertArrayHasKey('late', $trend);
        $this->assertArrayHasKey('absent', $trend);
        $this->assertContains(Carbon::parse($today)->format('M d'), $trend['labels']);
    }

    public function test_branch_admin_only_sees_scoped_attendance_dashboard_data(): void
    {
        $today = now()->toDateString();

        $b1Emp = Employee::factory()->create(['organization_id' => $this->organization->id, 'branch_id' => $this->branch1->id, 'status' => 'active']);
        $b2Emp = Employee::factory()->create(['organization_id' => $this->organization->id, 'branch_id' => $this->branch2->id, 'status' => 'active']);

        Attendance::factory()->create([
            'organization_id' => $this->organization->id,
            'employee_id' => $b1Emp->id,
            'date' => $today,
            'status' => 'present',
        ]);

        Attendance::factory()->create([
            'organization_id' => $this->organization->id,
            'employee_id' => $b2Emp->id,
            'date' => $today,
            'status' => 'present',
        ]);

        $response = $this->actingAs($this->branchAdmin)->get(route('attendances.dashboard'));
        $response->assertOk();
        $response->assertViewHas('totalEmployees', 1);
        $response->assertViewHas('presentToday', 1);
        $response->assertViewHas('absentToday', 0);
    }
}