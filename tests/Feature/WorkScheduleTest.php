<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WorkSchedule;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class WorkScheduleTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->forTenant($this->tenant)->create();
        $this->user->assignRole('super-admin');
        $this->organization = Organization::factory()->create(['tenant_id' => $this->tenant->id]);
    }

    public function test_unauthenticated_user_cannot_view(): void
    {
        $this->get(route('work-schedules.index'))->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view(): void
    {
        WorkSchedule::factory()->count(3)->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->get(route('work-schedules.index'))->assertOk();
    }

    public function test_authenticated_user_can_create(): void
    {
        $this->actingAs($this->user)
            ->post(route('work-schedules.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Standard Week',
                'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'hours_per_day' => 8,
            ])->assertRedirect();
        $this->assertDatabaseHas('work_schedules', ['name' => 'Standard Week']);
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('work-schedules.store'), [
                'organization_id' => $this->organization->id,
                'name' => '',
                'working_days' => ['Monday'],
                'hours_per_day' => 8,
            ])->assertSessionHasErrors('name');
    }

    public function test_organization_id_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('work-schedules.store'), [
                'organization_id' => '',
                'name' => 'Test',
                'working_days' => ['Monday'],
                'hours_per_day' => 8,
            ])->assertSessionHasErrors('organization_id');
    }

    public function test_working_days_is_required(): void
    {
        $this->actingAs($this->user)
            ->post(route('work-schedules.store'), [
                'organization_id' => $this->organization->id,
                'name' => 'Test',
                'working_days' => [],
                'hours_per_day' => 8,
            ])->assertSessionHasErrors('working_days');
    }

    public function test_authenticated_user_can_update(): void
    {
        $schedule = WorkSchedule::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Old',
        ]);
        $this->actingAs($this->user)
            ->put(route('work-schedules.update', $schedule), [
                'name' => 'New',
                'working_days' => ['Monday', 'Tuesday', 'Wednesday'],
                'hours_per_day' => 6,
                'status' => 'active',
            ])->assertRedirect();
        $this->assertDatabaseHas('work_schedules', ['id' => $schedule->id, 'name' => 'New']);
    }

    public function test_authenticated_user_can_delete(): void
    {
        $schedule = WorkSchedule::factory()->create(['organization_id' => $this->organization->id]);
        $this->actingAs($this->user)->delete(route('work-schedules.destroy', $schedule))->assertRedirect();
        $this->assertSoftDeleted('work_schedules', ['id' => $schedule->id]);
    }

    public function test_search_filters_results(): void
    {
        WorkSchedule::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Standard']);
        WorkSchedule::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Flexible']);
        WorkSchedule::factory()->create(['organization_id' => $this->organization->id, 'name' => 'Remote']);

        $this->actingAs($this->user)
            ->get(route('work-schedules.index', ['search' => 'Standard']))
            ->assertSee('Standard')
            ->assertDontSee('Remote');
    }
}
