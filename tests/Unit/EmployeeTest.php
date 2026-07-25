<?php

namespace Tests\Unit;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Organization;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmployeeTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_employee_belongs_to_organization(): void
    {
        $org = Organization::factory()->create();
        $employee = Employee::factory()->create(['organization_id' => $org->id]);

        $this->assertEquals($org->id, $employee->organization->id);
    }

    public function test_employee_full_name_attribute(): void
    {
        $employee = Employee::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $this->assertEquals('John Doe', $employee->full_name);
    }

    public function test_employee_uses_soft_deletes(): void
    {
        $employee = Employee::factory()->create();
        $id = $employee->id;
        $employee->delete();

        $this->assertSoftDeleted('employees', ['id' => $id]);
        $this->assertNull(Employee::find($id));
        $this->assertNotNull(Employee::withTrashed()->find($id));
    }

    public function test_employee_casts_hired_at_as_date(): void
    {
        $employee = Employee::factory()->create(['hired_at' => '2026-01-15']);
        $this->assertInstanceOf(Carbon::class, $employee->hired_at);
    }

    public function test_employee_casts_meta_as_array(): void
    {
        $employee = Employee::factory()->create(['meta' => ['skills' => ['PHP', 'Laravel']]]);
        $this->assertIsArray($employee->meta);
        $this->assertContains('PHP', $employee->meta['skills']);
    }

    public function test_employee_belongs_to_branch(): void
    {
        $branch = Branch::factory()->create();
        $employee = Employee::factory()->create(['branch_id' => $branch->id]);

        $this->assertEquals($branch->id, $employee->branch->id);
    }

    public function test_employee_belongs_to_department(): void
    {
        $dept = Department::factory()->create();
        $employee = Employee::factory()->create(['department_id' => $dept->id]);

        $this->assertEquals($dept->id, $employee->department->id);
    }

    public function test_employee_belongs_to_designation(): void
    {
        $desig = Designation::factory()->create();
        $employee = Employee::factory()->create(['designation_id' => $desig->id]);

        $this->assertEquals($desig->id, $employee->designation->id);
    }
}
