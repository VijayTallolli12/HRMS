<?php

namespace Tests\Unit;

use App\Models\Attendance;
use App\Models\Organization;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class AttendanceTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_attendance_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $attendance = Attendance::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($attendance->organization);
        $this->assertEquals($organization->id, $attendance->organization->id);
    }

    public function test_attendance_belongs_to_employee(): void
    {
        $attendance = Attendance::factory()->create();
        $this->assertNotNull($attendance->employee);
    }

    public function test_attendance_has_many_adjustments(): void
    {
        $attendance = Attendance::factory()->create();
        $this->assertIsObject($attendance);
        $this->assertNotNull($attendance->adjustments);
    }

    public function test_attendance_uses_soft_deletes(): void
    {
        $attendance = Attendance::factory()->create();
        $id = $attendance->id;
        $attendance->delete();
        $this->assertSoftDeleted('attendances', ['id' => $id]);
        $this->assertNull(Attendance::find($id));
        $this->assertNotNull(Attendance::withTrashed()->find($id));
    }
}
