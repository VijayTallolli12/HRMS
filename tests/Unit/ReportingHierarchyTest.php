<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\ReportingHierarchy;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ReportingHierarchyTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_reporting_hierarchy_belongs_to_employee(): void
    {
        $employee = Employee::factory()->create();
        $manager = Employee::factory()->create(['organization_id' => $employee->organization_id]);
        $hierarchy = ReportingHierarchy::factory()->create([
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);
        $this->assertNotNull($hierarchy->employee);
        $this->assertEquals($employee->id, $hierarchy->employee->id);
    }

    public function test_reporting_hierarchy_belongs_to_manager(): void
    {
        $employee = Employee::factory()->create();
        $manager = Employee::factory()->create(['organization_id' => $employee->organization_id]);
        $hierarchy = ReportingHierarchy::factory()->create([
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);
        $this->assertNotNull($hierarchy->manager);
        $this->assertEquals($manager->id, $hierarchy->manager->id);
    }

    public function test_reporting_hierarchy_uses_soft_deletes(): void
    {
        $employee = Employee::factory()->create();
        $manager = Employee::factory()->create(['organization_id' => $employee->organization_id]);
        $hierarchy = ReportingHierarchy::factory()->create([
            'employee_id' => $employee->id,
            'manager_id' => $manager->id,
        ]);
        $id = $hierarchy->id;
        $hierarchy->delete();
        $this->assertSoftDeleted('reporting_hierarchies', ['id' => $id]);
        $this->assertNull(ReportingHierarchy::find($id));
        $this->assertNotNull(ReportingHierarchy::withTrashed()->find($id));
    }
}
