<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\OvertimeRequest;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class OvertimeRequestTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_overtime_request_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $overtime = OvertimeRequest::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($overtime->organization);
        $this->assertEquals($organization->id, $overtime->organization->id);
    }

    public function test_overtime_request_belongs_to_employee(): void
    {
        $overtime = OvertimeRequest::factory()->create();
        $this->assertNotNull($overtime->employee);
    }

    public function test_overtime_request_uses_soft_deletes(): void
    {
        $overtime = OvertimeRequest::factory()->create();
        $id = $overtime->id;
        $overtime->delete();
        $this->assertSoftDeleted('overtime_requests', ['id' => $id]);
        $this->assertNull(OvertimeRequest::find($id));
        $this->assertNotNull(OvertimeRequest::withTrashed()->find($id));
    }
}
