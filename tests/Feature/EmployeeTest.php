<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmployeeTest extends TestCase
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

    public function test_unauthenticated_user_cannot_view_employees(): void
    {
        $this->get(route('employees.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_employees(): void
    {
        Employee::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('employees.index'))->assertOk();
    }

    public function test_authenticated_user_can_create_employee(): void
    {
        $this->actingAs($this->user)
            ->post(route('employees.store'), [
                'organization_id' => $this->organization->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
            ])->assertRedirect();
        $this->assertDatabaseHas('employees', ['first_name' => 'John', 'last_name' => 'Doe']);
    }

    public function test_employee_first_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('employees.store'), [
                'organization_id' => $this->organization->id,
                'first_name' => '',
                'last_name' => 'Doe',
            ])->assertSessionHasErrors('first_name');
    }

    public function test_employee_last_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('employees.store'), [
                'organization_id' => $this->organization->id,
                'first_name' => 'John',
                'last_name' => '',
            ])->assertSessionHasErrors('last_name');
    }

    public function test_employee_email_must_be_unique(): void
    {
        Employee::factory()->create(['email' => 'taken@example.com']);
        $this->actingAs($this->user)
            ->post(route('employees.store'), [
                'organization_id' => $this->organization->id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'taken@example.com',
            ])->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_view_employee(): void
    {
        $emp = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)
            ->get(route('employees.show', $emp))
            ->assertOk()
            ->assertSee($emp->first_name)
            ->assertSee($emp->last_name);
    }

    public function test_authenticated_user_can_update_employee(): void
    {
        $emp = Employee::factory()->create(['organization_id' => $this->organization->id, 'first_name' => 'Old']);
        $this->actingAs($this->user)
            ->put(route('employees.update', $emp), [
                'first_name' => 'New',
                'last_name' => $emp->last_name,
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('employees', ['id' => $emp->id, 'first_name' => 'New']);
    }

    public function test_authenticated_user_can_delete_employee(): void
    {
        $emp = Employee::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('employees.destroy', $emp))->assertRedirect();
        $this->assertSoftDeleted('employees', ['id' => $emp->id]);
    }

    public function test_search_filters_employees(): void
    {
        Employee::factory()->create(['organization_id' => $this->organization->id, 'first_name' => 'Alice', 'last_name' => 'Smith']);
        Employee::factory()->create(['organization_id' => $this->organization->id, 'first_name' => 'Bob', 'last_name' => 'Jones']);
        $this->actingAs($this->user)
            ->get(route('employees.index', ['search' => 'Alice']))
            ->assertSee('Alice')
            ->assertDontSee('Bob');
    }
}
