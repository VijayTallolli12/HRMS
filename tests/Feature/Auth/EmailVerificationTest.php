<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_returns_404(): void
    {
        $response = $this->get('/verify-email');

        $response->assertNotFound();
    }

    public function test_email_verification_endpoint_removed_in_v1(): void
    {
        $response = $this->get('/verify-email/test-id/test-hash');

        $response->assertNotFound();
    }
}
