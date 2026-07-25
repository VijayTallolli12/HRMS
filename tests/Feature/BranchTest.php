<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class BranchTest extends TestCase
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

    public function test_unauthenticated_user_cannot_view_branches(): void
    {
        $this->get(route('branches.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_branches(): void
    {
        Branch::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('branches.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_branch(): void
    {
        $this->actingAs($this->user)
            ->post(route('branches.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Headquarters',
            ])->assertRedirect();
        $this->assertDatabaseHas('branches', ['name' => 'Headquarters']);
    }

    public function test_branch_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('branches.store'), ['organization_id' => $this->organization->id, 'name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('branches.store'), ['name' => 'New Branch'])
            ->assertSessionHasErrors('organization_id');
    }

    public function test_authenticated_user_can_update_branch(): void
    {
        $branch = Branch::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('branches.update', $branch), ['name' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('branches', ['id' => $branch->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete_branch(): void
    {
        $branch = Branch::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('branches.destroy', $branch))->assertRedirect();
        $this->assertSoftDeleted('branches', ['id' => $branch->id]);
    }
}
