<?php

namespace Tests\Feature;

use App\Models\EmploymentType;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmploymentTypeTest extends TestCase
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
        $this->get(route('employment-types.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        EmploymentType::factory()->count(3)->create();
        $this->actingAs($this->user)->get(route('employment-types.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('employment-types.store'), [
                'name' => 'Full Time',
                'description' => 'Full time employment',
            ])->assertRedirect();
        $this->assertDatabaseHas('employment_types', ['name' => 'Full Time']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('employment-types.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_authenticated_user_can_update(): void
    {
        $type = EmploymentType::factory()->create(['name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('employment-types.update', $type), ['name' => 'New', 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('employment_types', ['id' => $type->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $type = EmploymentType::factory()->create();
        $this->actingAs($this->user)->delete(route('employment-types.destroy', $type))->assertRedirect();
        $this->assertSoftDeleted('employment_types', ['id' => $type->id]);
    }

    public function test_search_filters_results(): void
    {
        EmploymentType::factory()->create(['name' => 'Full Time']);
        EmploymentType::factory()->create(['name' => 'Part Time']);
        EmploymentType::factory()->create(['name' => 'Contract']);

        $this->actingAs($this->user)
            ->get(route('employment-types.index', ['search' => 'Full Time']))
            ->assertSee('Full Time')
            ->assertDontSee('Part Time');
    }
}
