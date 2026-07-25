<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class TenantTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->user = User::factory()->create();
        $this->user->assignRole('super-admin');
    }

    public function test_unauthenticated_user_cannot_view_tenants(): void
    {
        $this->get(route('tenants.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_tenants(): void
    {
        Tenant::factory()->count(3)->create();
        $this->actingAs($this->user)->get(route('tenants.index'))->assertOk();
    }

    public function test_tenant_index_displays_tenants(): void
    {
        Tenant::factory()->create(['name' => 'Test Tenant']);
        $this->actingAs($this->user)->get(route('tenants.index'))->assertSee('Test Tenant');
    }

    public function test_authenticated_user_can_create_tenant(): void
    {
        $this->actingAs($this->user)
            ->post(route('tenants.store'), ['name' => 'New Tenant', 'domain' => 'new.tenant.test'])
            ->assertRedirect();
        $this->assertDatabaseHas('tenants', ['name' => 'New Tenant']);
    }

    public function test_tenant_name_is_required(): void
    {
        $this->actingAs($this->user)->post(route('tenants.store'), ['name' => ''])->assertSessionHasErrors('name');
    }

    public function test_tenant_name_must_be_unique(): void
    {
        Tenant::factory()->create(['name' => 'Existing']);
        $this->actingAs($this->user)->post(route('tenants.store'), ['name' => 'Existing'])->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_view_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $this->actingAs($this->user)->get(route('tenants.show', $tenant))->assertOk()->assertSee($tenant->name);
    }

    public function test_authenticated_user_can_update_tenant(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'Old']);
        $this->actingAs($this->user)->put(route('tenants.update', $tenant), ['name' => 'New'])->assertRedirect();
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $this->actingAs($this->user)->delete(route('tenants.destroy', $tenant))->assertRedirect();
        $this->assertSoftDeleted('tenants', ['id' => $tenant->id]);
    }
}
