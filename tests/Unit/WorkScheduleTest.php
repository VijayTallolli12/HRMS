<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\WorkSchedule;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class WorkScheduleTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_work_schedule_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $schedule = WorkSchedule::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($schedule->organization);
        $this->assertEquals($organization->id, $schedule->organization->id);
    }

    public function test_work_schedule_casts_working_days_as_array(): void
    {
        $schedule = WorkSchedule::factory()->create([
            'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ]);
        $this->assertIsArray($schedule->working_days);
        $this->assertCount(5, $schedule->working_days);
        $this->assertContains('Monday', $schedule->working_days);
    }

    public function test_work_schedule_uses_soft_deletes(): void
    {
        $schedule = WorkSchedule::factory()->create();
        $id = $schedule->id;
        $schedule->delete();
        $this->assertSoftDeleted('work_schedules', ['id' => $id]);
        $this->assertNull(WorkSchedule::find($id));
        $this->assertNotNull(WorkSchedule::withTrashed()->find($id));
    }
}
