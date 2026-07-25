<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ShiftAssignmentTest extends TestCase
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
        $this->employee = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->shift = Shift::factory()->create(['organization_id' => $this->organization->id]);
    }

    public function test_unauthenticated_user_cannot_view(): void
    {
        $this->get(route('shift-assignments.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        ShiftAssignment::factory()->count(3)->create([
            'employee_id' => $this->employee->id,
            'shift_id' => $this->shift->id,
        ]);
        $this->actingAs($this->user)->get(route('shift-assignments.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('shift-assignments.store'), [
                'employee_id' => $this->employee->id,
                'shift_id' => $this->shift->id,
                'effective_from' => '2026-01-01',
            ])->assertRedirect();
        $this->assertDatabaseHas('shift_assignments', [
            'employee_id' => $this->employee->id,
            'shift_id' => $this->shift->id,
        ]);
    }

    public function test_employee_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('shift-assignments.store'), [
                'employee_id' => '',
                'shift_id' => $this->shift->id,
                'effective_from' => '2026-01-01',
            ])->assertSessionHasErrors('employee_id');
    }

    public function test_shift_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('shift-assignments.store'), [
                'employee_id' => $this->employee->id,
                'shift_id' => '',
                'effective_from' => '2026-01-01',
            ])->assertSessionHasErrors('shift_id');
    }

    public function test_effective_from_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('shift-assignments.store'), [
                'employee_id' => $this->employee->id,
                'shift_id' => $this->shift->id,
                'effective_from' => '',
            ])->assertSessionHasErrors('effective_from');
    }

    public function test_authenticated_user_can_update(): void
    {
        $assignment = ShiftAssignment::factory()->create([
            'employee_id' => $this->employee->id,
            'shift_id' => $this->shift->id,
            'effective_from' => '2026-01-01',
        ]);

        $newShift = Shift::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->put(route('shift-assignments.update', $assignment), [
                'employee_id' => $this->employee->id,
                'shift_id' => $newShift->id,
                'effective_from' => '2026-02-01',
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('shift_assignments', [
            'id' => $assignment->id,
            'shift_id' => $newShift->id,
        ]);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $assignment = ShiftAssignment::factory()->create([
            'employee_id' => $this->employee->id,
            'shift_id' => $this->shift->id,
        ]);
        $this->actingAs($this->user)->delete(route('shift-assignments.destroy', $assignment))->assertRedirect();
        $this->assertSoftDeleted('shift_assignments', ['id' => $assignment->id]);
    }
}
