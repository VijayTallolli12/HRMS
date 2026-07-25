<?php

namespace Tests\Unit;

use App\Models\LatePolicy;
use App\Models\Organization;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class LatePolicyTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_late_policy_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $policy = LatePolicy::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($policy->organization);
        $this->assertEquals($organization->id, $policy->organization->id);
    }

    public function test_late_policy_uses_soft_deletes(): void
    {
        $policy = LatePolicy::factory()->create();
        $id = $policy->id;
        $policy->delete();
        $this->assertSoftDeleted('late_policies', ['id' => $id]);
        $this->assertNull(LatePolicy::find($id));
        $this->assertNotNull(LatePolicy::withTrashed()->find($id));
    }

    public function test_late_policy_scope_active(): void
    {
        LatePolicy::factory()->create(['is_active' => true]);
        LatePolicy::factory()->create(['is_active' => false]);
        $this->assertEquals(1, LatePolicy::active()->count());
    }
}
