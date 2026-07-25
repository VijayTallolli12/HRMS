<?php

namespace Tests\Unit;

use App\Models\Holiday;
use App\Models\Organization;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class HolidayTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_holiday_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $holiday = Holiday::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($holiday->organization);
        $this->assertEquals($organization->id, $holiday->organization->id);
    }

    public function test_holiday_uses_soft_deletes(): void
    {
        $holiday = Holiday::factory()->create();
        $id = $holiday->id;
        $holiday->delete();
        $this->assertSoftDeleted('holidays', ['id' => $id]);
        $this->assertNull(Holiday::find($id));
        $this->assertNotNull(Holiday::withTrashed()->find($id));
    }
}
