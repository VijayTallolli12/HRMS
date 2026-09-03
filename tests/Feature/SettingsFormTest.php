<?php

namespace Tests\Feature;

use App\Livewire\SettingsForm;
use App\Models\ApplicationSetting;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\Traits\RefreshDatabaseAndRoles;

class SettingsFormTest extends TestCase
{
    use RefreshDatabaseAndRoles;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();

        $this->user = User::factory()->create();
        $this->user->assignRole('super-admin');
    }

    public function test_settings_page_renders_the_livewire_form(): void
    {
        $this->actingAs($this->user)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertSeeLivewire(SettingsForm::class);
    }

    public function test_general_settings_save_keeps_tab_and_reloads_values(): void
    {
        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'general')
            ->set('settings.company_name', 'Folkslogic HR')
            ->set('settings.email', 'admin@example.com')
            ->call('save')
            ->assertSet('activeTab', 'general')
            ->assertSet('settings.company_name', 'Folkslogic HR')
            ->assertSet('settings.email', 'admin@example.com')
            ->assertSee('Company Name')
            ->assertSee('Settings updated successfully.');

        $this->assertSame('Folkslogic HR', ApplicationSetting::get('company_name')['raw']);
    }

    public function test_branding_settings_save_keeps_tab_and_reloads_values(): void
    {
        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'branding')
            ->set('settings.app_title', 'People Ops')
            ->set('settings.primary_color', '#123abc')
            ->call('save')
            ->assertSet('activeTab', 'branding')
            ->assertSet('settings.app_title', 'People Ops')
            ->assertSet('settings.primary_color', '#123abc')
            ->assertSee('App Title')
            ->assertSee('Settings updated successfully.');

        $this->assertSame('People Ops', ApplicationSetting::get('app_title')['raw']);
    }

    public function test_email_settings_save_keeps_tab_and_reloads_values(): void
    {
        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'email')
            ->set('settings.smtp_host', 'smtp.example.com')
            ->set('settings.smtp_port', '587')
            ->set('settings.from_address', 'no-reply@example.com')
            ->set('settings.encryption', 'tls')
            ->call('save')
            ->assertSet('activeTab', 'email')
            ->assertSet('settings.smtp_host', 'smtp.example.com')
            ->assertSet('settings.smtp_port', '587')
            ->assertSee('SMTP Host')
            ->assertSee('Settings updated successfully.');

        $this->assertSame('smtp.example.com', ApplicationSetting::get('smtp_host')['raw']);
    }

    public function test_system_settings_save_keeps_tab_and_reloads_values(): void
    {
        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'system')
            ->set('settings.date_format', 'd/m/Y')
            ->set('settings.timezone', 'Asia/Kolkata')
            ->set('settings.currency', 'INR')
            ->call('save')
            ->assertSet('activeTab', 'system')
            ->assertSet('settings.date_format', 'd/m/Y')
            ->assertSet('settings.timezone', 'Asia/Kolkata')
            ->assertSet('settings.currency', 'INR')
            ->assertSee('Date Format')
            ->assertSee('Settings updated successfully.');

        $this->assertSame('Asia/Kolkata', ApplicationSetting::get('timezone')['raw']);
    }

    public function test_validation_errors_remain_visible_without_resetting_state(): void
    {
        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'email')
            ->set('settings.smtp_host', 'smtp.example.com')
            ->set('settings.from_address', 'not-an-email')
            ->call('save')
            ->assertHasErrors(['settings.from_address' => 'email'])
            ->assertSet('activeTab', 'email')
            ->assertSet('settings.smtp_host', 'smtp.example.com')
            ->assertSee('From Address');

        $this->assertNull(ApplicationSetting::where('key', 'smtp_host')->first());
    }

    public function test_logo_and_favicon_can_be_uploaded_and_saved_to_public_disk(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $logoFile = \Illuminate\Http\UploadedFile::fake()->image('custom-logo.png', 200, 200);
        $faviconFile = \Illuminate\Http\UploadedFile::fake()->image('custom-favicon.png', 32, 32);

        Livewire::actingAs($this->user)
            ->test(SettingsForm::class)
            ->set('activeTab', 'branding')
            ->set('logoUpload', $logoFile)
            ->set('faviconUpload', $faviconFile)
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('activeTab', 'branding')
            ->assertSee('Settings updated successfully.');

        $savedLogo = ApplicationSetting::get('logo')['raw'];
        $savedFavicon = ApplicationSetting::get('favicon')['raw'];

        $this->assertStringStartsWith('settings/', $savedLogo);
        $this->assertStringStartsWith('settings/', $savedFavicon);

        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($savedLogo);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($savedFavicon);
    }

    public function test_livewire_signed_upload_endpoint_accepts_signed_url_behind_trusted_proxy(): void
    {
        \Illuminate\Support\Facades\Storage::fake('tmp-for-tests');
        \Illuminate\Support\Facades\Storage::fake('public');

        config(['app.url' => 'https://hrms-production-b941.up.railway.app']);
        \Illuminate\Support\Facades\URL::forceRootUrl('https://hrms-production-b941.up.railway.app');
        \Illuminate\Support\Facades\URL::forceScheme('https');

        $file = \Illuminate\Http\UploadedFile::fake()->image('test-avatar.png', 100, 100);

        // Generate signed route as Livewire does in production
        $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'livewire.upload-file',
            now()->addMinutes(5)
        );

        $response = $this->actingAs($this->user)
            ->withServerVariables([
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'HTTP_X_FORWARDED_HOST' => 'hrms-production-b941.up.railway.app',
                'HTTP_X_FORWARDED_PORT' => '443',
            ])
            ->post($signedUrl, [
                'files' => [$file],
            ]);

        $response->assertOk();
        $this->assertArrayHasKey('paths', $response->json());
    }
}
