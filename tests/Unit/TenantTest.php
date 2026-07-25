<?php

namespace Tests\Unit;

use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class TenantTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
    }

    public function test_tenant_has_many_users(): void
    {
        $tenant = Tenant::factory()->create();
        User::factory()->count(3)->forTenant($tenant)->create();

        $this->assertCount(3, $tenant->users);
    }

    public function test_tenant_has_many_organizations(): void
    {
        $tenant = Tenant::factory()->create();
        Organization::factory()->count(2)->create(['tenant_id' => $tenant->id]);

        $this->assertCount(2, $tenant->organizations);
    }

    public function test_tenant_uses_soft_deletes(): void
    {
        $tenant = Tenant::factory()->create();
        $id = $tenant->id;
        $tenant->delete();

        $this->assertSoftDeleted('tenants', ['id' => $id]);
        $this->assertNull(Tenant::find($id));
        $this->assertNotNull(Tenant::withTrashed()->find($id));
    }

    public function test_tenant_casts_meta_as_array(): void
    {
        $tenant = Tenant::factory()->create(['meta' => ['key' => 'value']]);
        $this->assertIsArray($tenant->meta);
        $this->assertEquals('value', $tenant->meta['key']);
    }
}
