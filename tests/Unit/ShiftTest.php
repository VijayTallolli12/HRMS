<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\Shift;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class ShiftTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_shift_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $shift = Shift::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($shift->organization);
        $this->assertEquals($organization->id, $shift->organization->id);
    }

    public function test_shift_has_many_shift_assignments(): void
    {
        $shift = Shift::factory()->create();
        $this->assertIsObject($shift);
        $this->assertNotNull($shift->shiftAssignments);
    }

    public function test_shift_uses_soft_deletes(): void
    {
        $shift = Shift::factory()->create();
        $id = $shift->id;
        $shift->delete();
        $this->assertSoftDeleted('shifts', ['id' => $id]);
        $this->assertNull(Shift::find($id));
        $this->assertNotNull(Shift::withTrashed()->find($id));
    }
}
