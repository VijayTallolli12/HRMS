<?php

namespace Tests\Feature;

use App\Models\CostCenter;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class CostCenterTest extends TestCase
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
        $this->get(route('cost-centers.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        CostCenter::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('cost-centers.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('cost-centers.store'), [
                'organization_id' => $this->organization->id,
                'code' => 'CC-001',
                'name' => 'Engineering',
            ])->assertRedirect();
        $this->assertDatabaseHas('cost_centers', ['code' => 'CC-001', 'name' => 'Engineering']);
    }

    public function test_code_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('cost-centers.store'), [
                'organization_id' => $this->organization->id,
                'code' => '',
                'name' => 'Test',
            ])->assertSessionHasErrors('code');
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('cost-centers.store'), [
                'organization_id' => $this->organization->id,
                'code' => 'CC-002',
                'name' => '',
            ])->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('cost-centers.store'), [
                'organization_id' => '',
                'code' => 'CC-003',
                'name' => 'Test',
            ])->assertSessionHasErrors('organization_id');
    }

    public function test_authenticated_user_can_update(): void
    {
        $costCenter = CostCenter::factory()->create([
            'organization_id' => $this->organization->id,
            'code' => 'OLD',
            'name' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('cost-centers.update', $costCenter), [
                'code' => 'NEW',
                'name' => 'New',
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('cost_centers', ['id' => $costCenter->id, 'code' => 'NEW', 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $costCenter = CostCenter::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('cost-centers.destroy', $costCenter))->assertRedirect();
        $this->assertSoftDeleted('cost_centers', ['id' => $costCenter->id]);
    }

    public function test_search_filters_results(): void
    {
        CostCenter::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Engineering', 'code' => 'CC-001']);
        CostCenter::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Marketing', 'code' => 'CC-002']);
        CostCenter::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Finance', 'code' => 'CC-003']);

        $this->actingAs($this->user)
            ->get(route('cost-centers.index', ['search' => 'Engineering']))
            ->assertSee('Engineering')
            ->assertDontSee('Finance');
    }
}
