<?php

namespace Tests\Feature;

use App\Models\LatePolicy;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class LatePolicyTest extends TestCase
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
        $this->get(route('late-policies.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        LatePolicy::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('late-policies.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('late-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Standard Late Policy',
                'grace_minutes' => 10,
                'max_late_per_month' => 3,
                'penalty_type' => 'warning',
            ])->assertRedirect();
        $this->assertDatabaseHas('late_policies', ['name' => 'Standard Late Policy']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('late-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => '',
                'grace_minutes' => 10,
                'max_late_per_month' => 3,
                'penalty_type' => 'warning',
            ])->assertSessionHasErrors('name');
    }

    public function test_grace_minutes_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('late-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Standard Late Policy',
                'grace_minutes' => '',
                'max_late_per_month' => 3,
                'penalty_type' => 'warning',
            ])->assertSessionHasErrors('grace_minutes');
    }

    public function test_penalty_type_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('late-policies.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Standard Late Policy',
                'grace_minutes' => 10,
                'max_late_per_month' => 3,
                'penalty_type' => '',
            ])->assertSessionHasErrors('penalty_type');
    }

    public function test_authenticated_user_can_update(): void
    {
        $policy = LatePolicy::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Old Policy',
        ]);
        $this->actingAs($this->user)
            ->put(route('late-policies.update', $policy), [
                'name' => 'Updated Policy',
                'grace_minutes' => 15,
                'max_late_per_month' => 5,
                'penalty_type' => 'deduction',
                'is_active' => true,
            ])->assertRedirect();
        $this->assertDatabaseHas('late_policies', ['id' => $policy->id, 'name' => 'Updated Policy']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $policy = LatePolicy::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('late-policies.destroy', $policy))->assertRedirect();
        $this->assertSoftDeleted('late_policies', ['id' => $policy->id]);
    }

    public function test_search_filters_results(): void
    {
        LatePolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Standard']);
        LatePolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Strict']);
        LatePolicy::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Flexible']);

        $this->actingAs($this->user)
            ->get(route('late-policies.index', ['search' => 'Standard']))
            ->assertSee('Standard')
            ->assertDontSee('Flexible');
    }
}
