<?php

namespace Tests\Unit;

use App\Models\CostCenter;
use App\Models\Organization;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class CostCenterTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_cost_center_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $costCenter = CostCenter::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($costCenter->organization);
        $this->assertEquals($organization->id, $costCenter->organization->id);
    }

    public function test_cost_center_has_department_id(): void
    {
        $costCenter = CostCenter::factory()->create();
        $this->assertArrayHasKey('department_id', $costCenter->getAttributes());
    }

    public function test_cost_center_uses_soft_deletes(): void
    {
        $costCenter = CostCenter::factory()->create();
        $id = $costCenter->id;
        $costCenter->delete();
        $this->assertSoftDeleted('cost_centers', ['id' => $id]);
        $this->assertNull(CostCenter::find($id));
        $this->assertNotNull(CostCenter::withTrashed()->find($id));
    }
}
