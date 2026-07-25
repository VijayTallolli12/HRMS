<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WeekendPolicy;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class WeekendPolicyTest extends TestCase
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
        $this->get(route('weekend-policies.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        WeekendPolicy::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('weekend-policies.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('weekend-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Standard Weekend',
                'weekend_days' => ['Saturday', 'Sunday'],
            ])->assertRedirect();
        $this->assertDatabaseHas('weekend_policies', ['name' => 'Standard Weekend']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('weekend-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => '',
                'weekend_days' => ['Saturday'],
            ])->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('weekend-policies.store'), [
                'organization_id' => '',
                'name' => 'Test',
                'weekend_days' => ['Saturday'],
            ])->assertSessionHasErrors('organization_id');
    }

    public function test_weekend_days_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('weekend-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Test',
                'weekend_days' => [],
            ])->assertSessionHasErrors('weekend_days');
    }

    public function test_authenticated_user_can_update(): void
    {
        $policy = WeekendPolicy::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('weekend-policies.update', $policy), [
                'name' => 'New',
                'weekend_days' => ['Friday', 'Saturday'],
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('weekend_policies', ['id' => $policy->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $policy = WeekendPolicy::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('weekend-policies.destroy', $policy))->assertRedirect();
        $this->assertSoftDeleted('weekend_policies', ['id' => $policy->id]);
    }

    public function test_search_filters_results(): void
    {
        WeekendPolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Standard']);
        WeekendPolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Alternate']);
        WeekendPolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Flexible']);

        $this->actingAs($this->user)
            ->get(route('weekend-policies.index', ['search' => 'Standard']))
            ->assertSee('Standard')
            ->assertDontSee('Flexible');
    }
}
