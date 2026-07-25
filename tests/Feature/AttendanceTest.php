<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class AttendanceTest extends TestCase
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
        $this->get(route('attendances.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        Attendance::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('attendances.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('attendances.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => Carbon::now()->toDateString(),
                'clock_in' => '09:00',
                'clock_out' => '17:30',
                'status' => 'present',
            ])->assertRedirect();
        $this->assertDatabaseHas('attendances', ['employee_id' => $employee->id, 'status' => 'present']);
    }

    public function test_employee_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('attendances.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => '',
                'date' => Carbon::now()->toDateString(),
                'clock_in' => '09:00',
                'clock_out' => '17:30',
                'status' => 'present',
            ])->assertSessionHasErrors('employee_id');
    }

    public function test_date_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('attendances.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => '',
                'clock_in' => '09:00',
                'clock_out' => '17:30',
                'status' => 'present',
            ])->assertSessionHasErrors('date');
    }

    public function test_status_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('attendances.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => Carbon::now()->toDateString(),
                'clock_in' => '09:00',
                'clock_out' => '17:30',
                'status' => '',
            ])->assertSessionHasErrors('status');
    }

    public function test_authenticated_user_can_update(): void
    {
        $attendance = Attendance::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->put(route('attendances.update', $attendance), [
                'clock_in' => '08:00',
                'clock_out' => '18:00',
                'status' => 'late',
            ])->assertRedirect();
        $this->assertDatabaseHas('attendances', ['id' => $attendance->id, 'status' => 'late']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $attendance = Attendance::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('attendances.destroy', $attendance))->assertRedirect();
        $this->assertSoftDeleted('attendances', ['id' => $attendance->id]);
    }

    public function test_search_filters_results(): void
    {
        $employee1 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $employee2 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        Attendance::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee1->id, 'status' => 'present']);
        Attendance::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee2->id, 'status' => 'absent']);

        $this->actingAs($this->user)
            ->get(route('attendances.index', ['search' => $employee1->first_name]))
            ->assertSee($employee1->first_name);
    }
}
