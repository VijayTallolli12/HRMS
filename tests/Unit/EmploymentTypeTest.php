<?php

namespace Tests\Unit;

use App\Models\EmploymentType;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmploymentTypeTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_employment_type_has_many_employees(): void
    {
        $employmentType = EmploymentType::factory()->create();
        $this->assertIsObject($employmentType);
        $this->assertNotNull($employmentType->employees);
    }

    public function test_employment_type_uses_soft_deletes(): void
    {
        $employmentType = EmploymentType::factory()->create();
        $id = $employmentType->id;
        $employmentType->delete();
        $this->assertSoftDeleted('employment_types', ['id' => $id]);
        $this->assertNull(EmploymentType::find($id));
        $this->assertNotNull(EmploymentType::withTrashed()->find($id));
    }

    public function test_employment_type_casts_is_active(): void
    {
        $employmentType = EmploymentType::factory()->create(['is_active' => true]);
        $this->assertTrue($employmentType->is_active);

        $employmentType = EmploymentType::factory()->create(['is_active' => false]);
        $this->assertFalse($employmentType->is_active);
    }
}
