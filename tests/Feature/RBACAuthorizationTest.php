<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class RBACAuthorizationTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();

        $this->tenant = Tenant::create(['name' => 'Test Tenant', 'slug' => 'test-tenant']);
        $this->organization = Organization::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Org',
            'slug' => 'test-org',
        ]);

        $this->branch1 = Branch::create([
            'organization_id' => $this->organization->id,
            'name' => 'Branch 1',
            'code' => 'BR001',
        ]);

        $this->branch2 = Branch::create([
            'organization_id' => $this->organization->id,
            'name' => 'Branch 2',
            'code' => 'BR002',
        ]);

        $this->department = Department::create([
            'organization_id' => $this->organization->id,
            'name' => 'Engineering',
            'code' => 'ENG',
        ]);

        $this->designation = Designation::create([
            'organization_id' => $this->organization->id,
            'department_id' => $this->department->id,
            'title' => 'Developer',
        ]);

        $this->superAdmin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'organization_id' => $this->organization->id,
        ]);
        $this->superAdmin->assignRole('super-admin');

        $this->branchAdmin1 = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch1->id,
        ]);
        $this->branchAdmin1->assignRole('branch-admin');

        $this->branchAdmin2 = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch2->id,
        ]);
        $this->branchAdmin2->assignRole('branch-admin');

        $this->employee1 = Employee::factory()->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch1->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'created_by' => $this->superAdmin->id,
        ]);

        $this->employee2 = Employee::factory()->create([
            'organization_id' => $this->organization->id,
            'branch_id' => $this->branch2->id,
            'department_id' => $this->department->id,
            'designation_id' => $this->designation->id,
            'created_by' => $this->superAdmin->id,
        ]);
    }

    public function test_super_admin_can_access_any_resource(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get(route('employees.index'))->assertOk();
        $this->get(route('employees.show', $this->employee1))->assertOk();
        $this->get(route('branches.index'))->assertOk();
        $this->get(route('branches.show', $this->branch1))->assertOk();
    }

    public function test_branch_admin_can_view_employees_in_their_branch(): void
    {
        $this->actingAs($this->branchAdmin1);

        $this->get(route('employees.index'))->assertOk();
        $this->get(route('employees.show', $this->employee1))->assertOk();
    }

    public function test_branch_admin_denied_access_to_employees_in_another_branch(): void
    {
        $this->actingAs($this->branchAdmin1);

        $this->get(route('employees.show', $this->employee2))->assertForbidden();
    }

    public function test_branch_admin_can_view_branches_in_their_org(): void
    {
        $this->actingAs($this->branchAdmin1);

        $this->get(route('branches.index'))->assertOk();
        $this->get(route('branches.show', $this->branch1))->assertOk();
    }

    public function test_branch_admin_denied_access_to_tenants(): void
    {
        $this->actingAs($this->branchAdmin1);

        $this->get(route('tenants.index'))->assertForbidden();
    }

    public function test_super_admin_can_create_employees(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get(route('employees.create'))->assertOk();
    }

    public function test_branch_admin_can_create_employees_in_their_branch(): void
    {
        $this->actingAs($this->branchAdmin1);

        $this->get(route('employees.create'))->assertOk();
    }

    public function test_unauthenticated_denied_access_to_employee_index(): void
    {
        $this->get(route('employees.index'))->assertRedirect('/login');
    }
}
