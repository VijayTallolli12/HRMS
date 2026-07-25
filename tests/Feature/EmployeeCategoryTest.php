<?php

namespace Tests\Feature;

use App\Models\EmployeeCategory;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmployeeCategoryTest extends TestCase
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
        $this->get(route('employee-categories.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        EmployeeCategory::factory()->count(3)->create();
        $this->actingAs($this->user)->get(route('employee-categories.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('employee-categories.store'), [
                'name' => 'Senior',
                'description' => 'Senior level category',
                'level' => 3,
            ])->assertRedirect();
        $this->assertDatabaseHas('employee_categories', ['name' => 'Senior']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('employee-categories.store'), ['name' => '', 'level' => 1])
            ->assertSessionHasErrors('name');
    }

    public function test_level_is_optional(): void
    {
        $this->actingAs($this->user)
            ->post(route('employee-categories.store'), ['name' => 'Test'])
            ->assertRedirect();
        $this->assertDatabaseHas('employee_categories', ['name' => 'Test']);
    }

    public function test_authenticated_user_can_update(): void
    {
        $category = EmployeeCategory::factory()->create(['name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('employee-categories.update', $category), ['name' => 'New', 'level' => 5, 'status' => 'active'])
            ->assertRedirect();
        $this->assertDatabaseHas('employee_categories', ['id' => $category->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $category = EmployeeCategory::factory()->create();
        $this->actingAs($this->user)->delete(route('employee-categories.destroy', $category))->assertRedirect();
        $this->assertSoftDeleted('employee_categories', ['id' => $category->id]);
    }

    public function test_search_filters_results(): void
    {
        EmployeeCategory::factory()->create(['name' => 'Junior']);
        EmployeeCategory::factory()->create(['name' => 'Mid Level']);
        EmployeeCategory::factory()->create(['name' => 'Senior']);

        $this->actingAs($this->user)
            ->get(route('employee-categories.index', ['search' => 'Senior']))
            ->assertSee('Senior')
            ->assertDontSee('Junior');
    }
}
