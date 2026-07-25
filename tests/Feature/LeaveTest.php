<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class LeaveTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();

        $this->tenant = Tenant::create(['name' => 'Test Tenant', 'slug' => 'test-tenant']);
        $this->organization = Organization::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Org',
            'slug' => 'test-org',
        ]);
        $this->branch = Branch::create([
            'organization_id' => $this->organization->id,
            'name' => 'Main Branch',
            'code' => 'BR001',
        ]);
        $this->department = Department::create([
            'organization_id' => $this->organization->id,
            'name' => 'Engineering',
            'code' => 'ENG',
        ]);
        $this->designation = Designation::create([
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'title' => 'Developer',
        ]);

        $this->user = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'organization_id' => $this->organization->id,
        ]);
        $this->user->assignRole('super-admin');

        $this->employee = Employee::factory()->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'created_by' => $this->user->id,
        ]);

        $this->leave = Leave::factory()->create([
            'employee_id' => $this->employee->id,
            'organization_id' => $this->organization->id,
            'created_by' => $this->user->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_view_leaves(): void
    {
        $this->get(route('leaves.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_leaves(): void
    {
        $this->actingAs($this->user);
        $this->get(route('leaves.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_leave(): void
    {
        $this->actingAs($this->user);
        $this->get(route('leaves.create'))->assertOk();
    }

    public function test_employee_id_is_required(): void
    {
        $this->actingAs($this->user);
        $this->post(route('leaves.store'), [
            'employee_id' => '',
            'organization_id' => $this->organization->id,
            'leave_type' => 'annual',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-03',
            'days' => 3,
        ])->assertSessionHasErrors('employee_id');
    }

    public function test_leave_type_is_required(): void
    {
        $this->actingAs($this->user);
        $this->post(route('leaves.store'), [
            'employee_id' => $this->employee->id,
            'organization_id' => $this->organization->id,
            'leave_type' => '',
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-03',
            'days' => 3,
        ])->assertSessionHasErrors('leave_type');
    }

    public function test_authenticated_user_can_view_leave(): void
    {
        $this->actingAs($this->user);
        $this->get(route('leaves.show', $this->leave))->assertOk();
    }

    public function test_authenticated_user_can_update_leave(): void
    {
        $this->actingAs($this->user);
        $this->get(route('leaves.edit', $this->leave))->assertOk();
    }

    public function test_authenticated_user_can_delete_leave(): void
    {
        $this->actingAs($this->user);
        $this->delete(route('leaves.destroy', $this->leave))->assertRedirect();
        $this->assertSoftDeleted('leaves', ['id' => $this->leave->id]);
    }
}
