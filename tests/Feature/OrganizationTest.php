<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class OrganizationTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->forTenant($this->tenant)->create();
        $this->user->assignRole('super-admin');
    }

    public function test_unauthenticated_user_cannot_view_organizations(): void
    {
        $this->get(route('organizations.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_organizations(): void
    {
        Organization::factory()->count(3)->create(['tenant_id' => $this->tenant->id]);
        $this->actingAs($this->user)->get(route('organizations.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_organization(): void
    {
        $this->actingAs($this->user)
            ->post(route('organizations.store'), ['name' => 'Acme Corp', 'legal_name' => 'Acme Corp Ltd'])
            ->assertRedirect();
        $this->assertDatabaseHas('organizations', ['name' => 'Acme Corp']);
    }

    public function test_organization_name_is_required(): void
    {
        $this->actingAs($this->user)->post(route('organizations.store'), ['name' => ''])->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_update_organization(): void
    {
        $org = Organization::factory()->create(['name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('organizations.update', $org), ['name' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('organizations', ['id' => $org->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete_organization(): void
    {
        $org = Organization::factory()->create();
        $this->actingAs($this->user)->delete(route('organizations.destroy', $org))->assertRedirect();
        $this->assertSoftDeleted('organizations', ['id' => $org->id]);
    }

    public function test_search_filters_organizations(): void
    {
        Organization::factory()->create(['name' => 'Alpha Corp', 'tenant_id' => $this->tenant->id]);
        Organization::factory()->create(['name' => 'Beta Inc', 'tenant_id' => $this->tenant->id]);
        $this->actingAs($this->user)
            ->get(route('organizations.index', ['search' => 'Alpha']))
            ->assertSee('Alpha Corp')
            ->assertDontSee('Beta Inc');
    }
}
