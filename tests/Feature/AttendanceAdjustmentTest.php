<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceAdjustment;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class AttendanceAdjustmentTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->forTenant($this->tenant)->create();
        $this->user->assignRole('super-admin');
        $this->organization = Organization::factory()->create(['tenant_id' => $this->tenant->id]);
    }

    public function test_unauthenticated_user_cannot_view(): void
    {
        $this->get(route('attendance-adjustments.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        AttendanceAdjustment::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('attendance-adjustments.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $attendance = Attendance::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee->id]);
        $this->actingAs($this->user)
            ->post(route('attendance-adjustments.store'), [
                'organization_id' => $this->organization->id,
                'attendance_id' => $attendance->id,
                'employee_id' => $employee->id,
                'reason' => 'Forgot to clock in',
                'new_clock_in' => '09:00',
                'new_status' => 'present',
            ])->assertRedirect();
        $this->assertDatabaseHas('attendance_adjustments', ['reason' => 'Forgot to clock in']);
    }

    public function test_reason_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $attendance = Attendance::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee->id]);
        $this->actingAs($this->user)
            ->post(route('attendance-adjustments.store'), [
                'organization_id' => $this->organization->id,
                'attendance_id' => $attendance->id,
                'employee_id' => $employee->id,
                'reason' => '',
                'new_clock_in' => '09:00',
                'new_status' => 'present',
            ])->assertSessionHasErrors('reason');
    }

    public function test_status_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $attendance = Attendance::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee->id]);
        $this->actingAs($this->user)
            ->post(route('attendance-adjustments.store'), [
                'organization_id' => $this->organization->id,
                'attendance_id' => $attendance->id,
                'employee_id' => $employee->id,
                'reason' => 'Forgot to clock in',
                'new_clock_in' => '09:00',
                'new_status' => '',
            ])->assertSessionHasErrors('new_status');
    }

    public function test_authenticated_user_can_update(): void
    {
        $adjustment = AttendanceAdjustment::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->put(route('attendance-adjustments.update', $adjustment), [
                'reason' => 'Forgot to clock in',
                'new_clock_in' => '09:00',
                'new_status' => 'present',
                'status' => 'approved',
                'approved_by' => $this->user->id,
            ])->assertRedirect();
        $this->assertDatabaseHas('attendance_adjustments', ['id' => $adjustment->id, 'status' => 'approved']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $adjustment = AttendanceAdjustment::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('attendance-adjustments.destroy', $adjustment))->assertRedirect();
        $this->assertSoftDeleted('attendance_adjustments', ['id' => $adjustment->id]);
    }

    public function test_search_filters_results(): void
    {
        $employee1 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $employee2 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        AttendanceAdjustment::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee1->id, 'reason' => 'Forgot to clock in']);
        AttendanceAdjustment::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee2->id, 'reason' => 'System error']);

        $this->actingAs($this->user)
            ->get(route('attendance-adjustments.index', ['search' => $employee1->first_name]))
            ->assertSee($employee1->first_name);
    }
}
