<?php

namespace Tests\Unit;

use App\Models\EmploymentStatus;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmploymentStatusTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_employment_status_has_many_employees(): void
    {
        $status = EmploymentStatus::factory()->create();
        $this->assertIsObject($status);
        $this->assertNotNull($status->employees);
    }

    public function test_employment_status_uses_soft_deletes(): void
    {
        $status = EmploymentStatus::factory()->create();
        $id = $status->id;
        $status->delete();
        $this->assertSoftDeleted('employment_statuses', ['id' => $id]);
        $this->assertNull(EmploymentStatus::find($id));
        $this->assertNotNull(EmploymentStatus::withTrashed()->find($id));
    }
}
