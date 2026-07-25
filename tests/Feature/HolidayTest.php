<?php

namespace Tests\Feature;

use App\Models\Holiday;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class HolidayTest extends TestCase
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
        $this->get(route('holidays.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        Holiday::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('holidays.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('holidays.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Christmas',
                'date' => '2026-12-25',
                'type' => 'public',
            ])->assertRedirect();
        $this->assertDatabaseHas('holidays', ['name' => 'Christmas']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('holidays.store'), [
                'organization_id' => $this->organization->id,
                'name' => '',
                'date' => '2026-12-25',
                'type' => 'public',
            ])->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('holidays.store'), [
                'organization_id' => '',
                'name' => 'Test',
                'date' => '2026-12-25',
                'type' => 'public',
            ])->assertSessionHasErrors('organization_id');
    }

    public function test_date_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('holidays.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Test',
                'date' => '',
                'type' => 'public',
            ])->assertSessionHasErrors('date');
    }

    public function test_authenticated_user_can_update(): void
    {
        $holiday = Holiday::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('holidays.update', $holiday), [
                'name' => 'New',
                'date' => '2026-07-04',
                'type' => 'public',
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('holidays', ['id' => $holiday->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $holiday = Holiday::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('holidays.destroy', $holiday))->assertRedirect();
        $this->assertSoftDeleted('holidays', ['id' => $holiday->id]);
    }

    public function test_search_filters_results(): void
    {
        Holiday::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Christmas']);
        Holiday::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Easter']);
        Holiday::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Thanksgiving']);

        $this->actingAs($this->user)
            ->get(route('holidays.index', ['search' => 'Christmas']))
            ->assertSee('Christmas')
            ->assertDontSee('Easter');
    }
}
