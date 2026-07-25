<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class DesignationTest extends TestCase
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
        $this->department = Department::factory()->create(['organization_id' => $this->organization->id]);
    }

    public function test_unauthenticated_user_cannot_view_designations(): void
    {
        $this->get(route('designations.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_designations(): void
    {
        Designation::factory()->create([
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
        ]);
        $this->actingAs($this->user)->get(route('designations.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_designation(): void
    {
        $this->actingAs($this->user)
            ->post(route('designations.store'), [
                'organization_id' => $this->organization->id,
                'department_id' => $this->department->id,
                'title' => 'Senior Engineer',
                'level' => 'L4',
            ])->assertRedirect();
        $this->assertDatabaseHas('designations', ['title' => 'Senior Engineer']);
    }

    public function test_designation_title_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('designations.store'), [
                'organization_id' => $this->organization->id,
                'department_id' => $this->department->id,
                'title' => '',
            ])->assertSessionHasErrors('title');
    }

    public function test_authenticated_user_can_update_designation(): void
    {
        $desig = Designation::factory()->create([
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'title' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('designations.update', $desig), ['title' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('designations', ['id' => $desig->id, 'title' => 'New']);
    }

    public function test_authenticated_user_can_delete_designation(): void
    {
        $desig = Designation::factory()->create([
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
        ]);
        $this->actingAs($this->user)->delete(route('designations.destroy', $desig))->assertRedirect();
        $this->assertSoftDeleted('designations', ['id' => $desig->id]);
    }
}
