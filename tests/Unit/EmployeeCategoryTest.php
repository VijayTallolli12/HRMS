<?php

namespace Tests\Unit;

use App\Models\EmployeeCategory;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class EmployeeCategoryTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_employee_category_has_many_employees(): void
    {
        $category = EmployeeCategory::factory()->create();
        $this->assertIsObject($category);
        $this->assertNotNull($category->employees);
    }

    public function test_employee_category_uses_soft_deletes(): void
    {
        $category = EmployeeCategory::factory()->create();
        $id = $category->id;
        $category->delete();
        $this->assertSoftDeleted('employee_categories', ['id' => $id]);
        $this->assertNull(EmployeeCategory::find($id));
        $this->assertNotNull(EmployeeCategory::withTrashed()->find($id));
    }
}
