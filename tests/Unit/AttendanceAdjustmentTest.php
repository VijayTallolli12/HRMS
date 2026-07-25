<?php

namespace Tests\Unit;

use App\Models\AttendanceAdjustment;
use App\Models\Organization;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class AttendanceAdjustmentTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_adjustment_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $adjustment = AttendanceAdjustment::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($adjustment->organization);
        $this->assertEquals($organization->id, $adjustment->organization->id);
    }

    public function test_adjustment_belongs_to_attendance(): void
    {
        $adjustment = AttendanceAdjustment::factory()->create();
        $this->assertNotNull($adjustment->attendance);
    }

    public function test_adjustment_belongs_to_employee(): void
    {
        $adjustment = AttendanceAdjustment::factory()->create();
        $this->assertNotNull($adjustment->employee);
    }

    public function test_adjustment_uses_soft_deletes(): void
    {
        $adjustment = AttendanceAdjustment::factory()->create();
        $id = $adjustment->id;
        $adjustment->delete();
        $this->assertSoftDeleted('attendance_adjustments', ['id' => $id]);
        $this->assertNull(AttendanceAdjustment::find($id));
        $this->assertNotNull(AttendanceAdjustment::withTrashed()->find($id));
    }
}
