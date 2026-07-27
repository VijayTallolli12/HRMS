<?php

namespace App\Livewire;

use App\Models\ApplicationSetting;
use App\Services\BrandingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public string $activeTab = 'general';

    public array $settings = [
        'company_name' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'website' => '',
        'app_title' => '',
        'primary_color' => '#4f46e5',
        'logo' => '',
        'favicon' => '',
        'smtp_host' => '',
        'smtp_port' => '',
        'from_address' => '',
        'from_name' => '',
        'encryption' => 'tls',
        'date_format' => 'Y-m-d',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ];

    public $logoUpload = null;

    public $faviconUpload = null;

    private array $tabs = [
        'general' => ['company_name', 'address', 'phone', 'email', 'website'],
        'branding' => ['app_title', 'primary_color', 'logo', 'favicon'],
        'email' => ['smtp_host', 'smtp_port', 'from_address', 'from_name', 'encryption'],
        'system' => ['date_format', 'timezone', 'currency'],
    ];

    public function mount(): void
    {
        $this->authorize('view-settings');

        $this->reloadSettings();
    }

    public function setActiveTab(string $tab): void
    {
        if (! array_key_exists($tab, $this->tabs)) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function save(): void
    {
        $this->authorize('update-settings');
        $this->activeTab = $this->validTab($this->activeTab);

        $validated = $this->validate($this->rulesForActiveTab());

        foreach ($this->tabs[$this->activeTab] as $key) {
            if (in_array($key, ['logo', 'favicon'], true)) {
                $uploadProperty = $key === 'logo' ? 'logoUpload' : 'faviconUpload';

                if ($this->{$uploadProperty}) {
                    $this->settings[$key] = $this->{$uploadProperty}->store('settings', 'public');
                    $this->{$uploadProperty} = null;
                }
            }

            $value = $this->settings[$key] ?? null;

            if ($value === null || $value === '') {
                ApplicationSetting::where('key', $key)->delete();

                continue;
            }

            ApplicationSetting::set($key, $value, $this->activeTab);
        }

        $this->reloadSettings();
        app(BrandingService::class)->refresh();
        $this->resetValidation();

        session()->flash('success', 'Settings updated successfully.');
        $this->dispatch('branding-updated', branding: app(BrandingService::class)->all());
    }

    public function render()
    {
        return view('livewire.settings-form');
    }

    private function reloadSettings(): void
    {
        $savedSettings = ApplicationSetting::orderBy('key')->get()
            ->mapWithKeys(fn (ApplicationSetting $setting) => [
                $setting->key => is_array($setting->value)
                    ? ($setting->value['raw'] ?? reset($setting->value))
                    : $setting->value,
            ])
            ->toArray();

        $this->settings = array_merge($this->settings, $savedSettings);
        $this->activeTab = $this->validTab($this->activeTab);
    }

    private function rulesForActiveTab(): array
    {
        $rules = [
            'settings.company_name' => ['nullable', 'string', 'max:255'],
            'settings.address' => ['nullable', 'string'],
            'settings.phone' => ['nullable', 'string', 'max:255'],
            'settings.email' => ['nullable', 'email', 'max:255'],
            'settings.website' => ['nullable', 'url', 'max:255'],
            'settings.app_title' => ['nullable', 'string', 'max:255'],
            'settings.primary_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'logoUpload' => ['nullable', 'image', 'max:2048'],
            'faviconUpload' => ['nullable', 'image', 'max:2048'],
            'settings.smtp_host' => ['nullable', 'string', 'max:255'],
            'settings.smtp_port' => ['nullable', 'integer'],
            'settings.from_address' => ['nullable', 'email', 'max:255'],
            'settings.from_name' => ['nullable', 'string', 'max:255'],
            'settings.encryption' => ['nullable', Rule::in(['tls', 'ssl'])],
            'settings.date_format' => ['nullable', 'string', 'max:20'],
            'settings.timezone' => ['nullable', 'timezone'],
            'settings.currency' => ['nullable', 'string', 'max:5'],
        ];

        $activeFields = collect($this->tabs[$this->activeTab])
            ->flatMap(fn (string $field) => match ($field) {
                'logo' => ['logoUpload'],
                'favicon' => ['faviconUpload'],
                default => ["settings.{$field}"],
            });

        return collect($rules)->only($activeFields)->all();
    }

    private function validTab(string $tab): string
    {
        return array_key_exists($tab, $this->tabs) ? $tab : 'general';
    }
}
