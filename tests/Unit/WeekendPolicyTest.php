<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\WeekendPolicy;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class WeekendPolicyTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_weekend_policy_belongs_to_organization(): void
    {
        $organization = Organization::factory()->create();
        $policy = WeekendPolicy::factory()->create(['organization_id' => $organization->id]);
        $this->assertNotNull($policy->organization);
        $this->assertEquals($organization->id, $policy->organization->id);
    }

    public function test_weekend_policy_casts_weekend_days_as_array(): void
    {
        $policy = WeekendPolicy::factory()->create([
            'weekend_days' => ['Saturday', 'Sunday'],
        ]);
        $this->assertIsArray($policy->weekend_days);
        $this->assertCount(2, $policy->weekend_days);
        $this->assertContains('Saturday', $policy->weekend_days);
        $this->assertContains('Sunday', $policy->weekend_days);
    }

    public function test_weekend_policy_uses_soft_deletes(): void
    {
        $policy = WeekendPolicy::factory()->create();
        $id = $policy->id;
        $policy->delete();
        $this->assertSoftDeleted('weekend_policies', ['id' => $id]);
        $this->assertNull(WeekendPolicy::find($id));
        $this->assertNotNull(WeekendPolicy::withTrashed()->find($id));
    }
}
