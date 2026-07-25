<?php

namespace Tests\Feature;

use App\Models\EmploymentStatus;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmploymentStatusTest extends TestCase
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

    public function test_unauthenticated_user_cannot_view(): void
    {
        $this->get(route('employment-statuses.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        EmploymentStatus::factory()->count(3)->create();
        $this->actingAs($this->user)->get(route('employment-statuses.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('employment-statuses.store'), [
                'name' => 'Active',
                'description' => 'Currently employed',
            ])->assertRedirect();
        $this->assertDatabaseHas('employment_statuses', ['name' => 'Active']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('employment-statuses.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_update(): void
    {
        $status = EmploymentStatus::factory()->create(['name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('employment-statuses.update', $status), ['name' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('employment_statuses', ['id' => $status->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $status = EmploymentStatus::factory()->create();
        $this->actingAs($this->user)->delete(route('employment-statuses.destroy', $status))->assertRedirect();
        $this->assertSoftDeleted('employment_statuses', ['id' => $status->id]);
    }

    public function test_search_filters_results(): void
    {
        EmploymentStatus::factory()->create(['name' => 'Active']);
        EmploymentStatus::factory()->create(['name' => 'Inactive']);
        EmploymentStatus::factory()->create(['name' => 'On Leave']);

        $this->actingAs($this->user)
            ->get(route('employment-statuses.index', ['search' => 'Active']))
            ->assertSee('Active')
            ->assertDontSee('On Leave');
    }
}
