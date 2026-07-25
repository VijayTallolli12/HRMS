<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_organization()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post(route('organizations.store'), [
                'name' => 'Acme Corp',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('organizations', ['name' => 'Acme Corp']);
    }
}
