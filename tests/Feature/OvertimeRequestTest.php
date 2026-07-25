<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\OvertimeRequest;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class OvertimeRequestTest extends TestCase
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
        $this->get(route('overtime-requests.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        OvertimeRequest::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('overtime-requests.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('overtime-requests.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => now()->toDateString(),
                'hours' => 3.5,
                'reason' => 'Project deadline',
            ])->assertRedirect();
        $this->assertDatabaseHas('overtime_requests', ['hours' => 3.5, 'reason' => 'Project deadline']);
    }

    public function test_hours_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('overtime-requests.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => now()->toDateString(),
                'hours' => '',
                'reason' => 'Project deadline',
            ])->assertSessionHasErrors('hours');
    }

    public function test_reason_is_required(): void
    {
        $employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->post(route('overtime-requests.store'), [
                'organization_id' => $this->organization->id,
                'employee_id' => $employee->id,
                'date' => now()->toDateString(),
                'hours' => 3.5,
                'reason' => '',
            ])->assertSessionHasErrors('reason');
    }

    public function test_authenticated_user_can_update(): void
    {
        $overtime = OvertimeRequest::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->put(route('overtime-requests.update', $overtime), [
                'hours' => 3.5,
                'reason' => 'Project deadline',
                'status' => 'approved',
            ])->assertRedirect();
        $this->assertDatabaseHas('overtime_requests', ['id' => $overtime->id, 'status' => 'approved']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $overtime = OvertimeRequest::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('overtime-requests.destroy', $overtime))->assertRedirect();
        $this->assertSoftDeleted('overtime_requests', ['id' => $overtime->id]);
    }

    public function test_search_filters_results(): void
    {
        $employee1 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $employee2 = Employee::factory()->create(['organization_id' => $this->organization->id]);
        OvertimeRequest::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee1->id, 'reason' => 'Project deadline']);
        OvertimeRequest::factory()->create(['organization_id' => $this->organization->id, 'employee_id' => $employee2->id, 'reason' => 'Client meeting']);

        $this->actingAs($this->user)
            ->get(route('overtime-requests.index', ['search' => $employee1->first_name]))
            ->assertSee($employee1->first_name);
    }
}
