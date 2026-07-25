<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class DepartmentTest extends TestCase
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

    public function test_unauthenticated_user_cannot_view_departments(): void
    {
        $this->get(route('departments.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_departments(): void
    {
        Department::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('departments.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_department(): void
    {
        $this->actingAs($this->user)
            ->post(route('departments.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Engineering',
            ])->assertRedirect();
        $this->assertDatabaseHas('departments', ['name' => 'Engineering']);
    }

    public function test_department_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('departments.store'), ['organization_id' => $this->organization->id, 'name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_update_department(): void
    {
        $dept = Department::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('departments.update', $dept), ['name' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('departments', ['id' => $dept->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete_department(): void
    {
        $dept = Department::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('departments.destroy', $dept))->assertRedirect();
        $this->assertSoftDeleted('departments', ['id' => $dept->id]);
    }
}
