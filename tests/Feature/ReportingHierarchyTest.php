<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\ReportingHierarchy;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ReportingHierarchyTest extends TestCase
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
        $this->manager = Employee::factory()->create(['organization_id' => $this->organization->id]);
    }

    public function test_unauthenticated_user_cannot_view(): void
    {
        $this->get(route('reporting-hierarchies.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        ReportingHierarchy::factory()->count(3)->create([
            'employee_id' => $this->employee->id,
            'manager_id' => $this->manager->id,
        ]);
        $this->actingAs($this->user)->get(route('reporting-hierarchies.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('reporting-hierarchies.store'), [
                'employee_id' => $this->employee->id,
                'manager_id' => $this->manager->id,
                'reporting_type' => 'direct',
                'effective_from' => '2026-01-01',
            ])->assertRedirect();
        $this->assertDatabaseHas('reporting_hierarchies', [
            'employee_id' => $this->employee->id,
            'manager_id' => $this->manager->id,
        ]);
    }

    public function test_employee_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('reporting-hierarchies.store'), [
                'employee_id' => '',
                'manager_id' => $this->manager->id,
                'reporting_type' => 'direct',
                'effective_from' => '2026-01-01',
            ])->assertSessionHasErrors('employee_id');
    }

    public function test_manager_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('reporting-hierarchies.store'), [
                'employee_id' => $this->employee->id,
                'manager_id' => '',
                'reporting_type' => 'direct',
                'effective_from' => '2026-01-01',
            ])->assertSessionHasErrors('manager_id');
    }

    public function test_effective_from_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('reporting-hierarchies.store'), [
                'employee_id' => $this->employee->id,
                'manager_id' => $this->manager->id,
                'reporting_type' => 'direct',
                'effective_from' => '',
            ])->assertSessionHasErrors('effective_from');
    }

    public function test_authenticated_user_can_update(): void
    {
        $hierarchy = ReportingHierarchy::factory()->create([
            'employee_id' => $this->employee->id,
            'manager_id' => $this->manager->id,
        ]);

        $newManager = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->put(route('reporting-hierarchies.update', $hierarchy), [
                'employee_id' => $this->employee->id,
                'manager_id' => $newManager->id,
                'reporting_type' => 'indirect',
                'effective_from' => '2026-02-01',
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('reporting_hierarchies', [
            'id' => $hierarchy->id,
            'manager_id' => $newManager->id,
        ]);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $hierarchy = ReportingHierarchy::factory()->create([
            'employee_id' => $this->employee->id,
            'manager_id' => $this->manager->id,
        ]);
        $this->actingAs($this->user)->delete(route('reporting-hierarchies.destroy', $hierarchy))->assertRedirect();
        $this->assertSoftDeleted('reporting_hierarchies', ['id' => $hierarchy->id]);
    }
}
