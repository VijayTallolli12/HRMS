<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ShiftAssignmentTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_shift_assignment_belongs_to_employee(): void
    {
        $employee = Employee::factory()->create();
        $shift = Shift::factory()->create(['organization_id' => $employee->organization_id]);
        $assignment = ShiftAssignment::factory()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
        ]);
        $this->assertNotNull($assignment->employee);
        $this->assertEquals($employee->id, $assignment->employee->id);
    }

    public function test_shift_assignment_belongs_to_shift(): void
    {
        $employee = Employee::factory()->create();
        $shift = Shift::factory()->create(['organization_id' => $employee->organization_id]);
        $assignment = ShiftAssignment::factory()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
        ]);
        $this->assertNotNull($assignment->shift);
        $this->assertEquals($shift->id, $assignment->shift->id);
    }

    public function test_shift_assignment_uses_soft_deletes(): void
    {
        $employee = Employee::factory()->create();
        $shift = Shift::factory()->create(['organization_id' => $employee->organization_id]);
        $assignment = ShiftAssignment::factory()->create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
        ]);
        $id = $assignment->id;
        $assignment->delete();
        $this->assertSoftDeleted('shift_assignments', ['id' => $id]);
        $this->assertNull(ShiftAssignment::find($id));
        $this->assertNotNull(ShiftAssignment::withTrashed()->find($id));
    }
}
