<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Shift;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ShiftTest extends TestCase
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
        $this->get(route('shifts.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        Shift::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('shifts.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('shifts.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Morning',
                'start_time' => '09:00',
                'end_time' => '17:00',
            ])->assertRedirect();
        $this->assertDatabaseHas('shifts', ['name' => 'Morning']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('shifts.store'), [
                'organization_id' => $this->organization->id,
                'name' => '',
                'start_time' => '09:00',
                'end_time' => '17:00',
            ])->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('shifts.store'), [
                'organization_id' => '',
                'name' => 'Morning',
                'start_time' => '09:00',
                'end_time' => '17:00',
            ])->assertSessionHasErrors('organization_id');
    }

    public function test_authenticated_user_can_update(): void
    {
        $shift = Shift::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('shifts.update', $shift), [
                'name' => 'New',
                'start_time' => '08:00',
                'end_time' => '16:00',
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('shifts', ['id' => $shift->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $shift = Shift::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('shifts.destroy', $shift))->assertRedirect();
        $this->assertSoftDeleted('shifts', ['id' => $shift->id]);
    }

    public function test_search_filters_results(): void
    {
        Shift::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Morning']);
        Shift::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Evening']);
        Shift::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Night']);

        $this->actingAs($this->user)
            ->get(route('shifts.index', ['search' => 'Morning']))
            ->assertSee('Morning')
            ->assertDontSee('Night');
    }
}
