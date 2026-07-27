<?php

namespace Tests\Feature;

use App\Models\ApplicationSetting;
use App\Models\User;
use App\Services\BrandingService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class BrandingRenderingTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
        Storage::disk('public')->put('settings/company-logo.png', 'logo');
        Storage::disk('public')->put('settings/company-favicon.ico', 'favicon');

        ApplicationSetting::set('company_name', 'Acme People', 'general');
        ApplicationSetting::set('app_title', 'Acme HR Portal', 'branding');
        ApplicationSetting::set('logo', 'settings/company-logo.png', 'branding');
        ApplicationSetting::set('favicon', 'settings/company-favicon.ico', 'branding');
    }

    public function test_branding_service_loads_database_values_and_storage_urls(): void
    {
        $branding = app(BrandingService::class)->all();

        $this->assertSame('Acme HR Portal', $branding['app_name']);
        $this->assertSame('Acme People', $branding['company_name']);
        $this->assertSame('/storage/settings/company-logo.png', $branding['logo_url']);
        $this->assertSame('/storage/settings/company-favicon.ico', $branding['favicon_url']);
    }

    public function test_sidebar_and_main_layout_use_saved_branding(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('<title>Acme HR Portal</title>', false)
            ->assertSee('<link rel="icon" href="/storage/settings/company-favicon.ico">', false)
            ->assertSee('src="/storage/settings/company-logo.png"', false)
            ->assertSee('Acme People');
    }

    public function test_login_layout_uses_saved_branding(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<title>Acme HR Portal</title>', false)
            ->assertSee('<link rel="icon" href="/storage/settings/company-favicon.ico">', false)
            ->assertSee('src="/storage/settings/company-logo.png"', false)
            ->assertSee('Acme People');
    }

    public function test_missing_uploaded_files_fall_back_gracefully(): void
    {
        ApplicationSetting::set('logo', 'settings/missing-logo.png', 'branding');
        ApplicationSetting::set('favicon', 'settings/missing-favicon.ico', 'branding');
        app(BrandingService::class)->refresh();

        $branding = app(BrandingService::class)->all();

        $this->assertNull($branding['logo_url']);
        $this->assertSame('/favicon.ico', $branding['favicon_url']);
    }
}
