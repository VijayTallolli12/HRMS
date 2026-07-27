<?php

namespace App\Services;

use App\Models\ApplicationSetting;
use Illuminate\Support\Facades\Storage;

class BrandingService
{
    private ?array $branding = null;

    public function all(): array
    {
        if ($this->branding !== null) {
            return $this->branding;
        }

        $settings = ApplicationSetting::whereIn('key', [
            'app_title',
            'company_name',
            'logo',
            'favicon',
        ])->get()->mapWithKeys(fn (ApplicationSetting $setting) => [
            $setting->key => $this->rawValue($setting->value),
        ]);

        $appName = $this->clean($settings->get('app_title'))
            ?: $this->clean($settings->get('company_name'))
            ?: config('app.name', 'HRMS');

        $companyName = $this->clean($settings->get('company_name')) ?: $appName;

        return $this->branding = [
            'app_name' => $appName,
            'company_name' => $companyName,
            'logo_url' => $this->publicDiskUrl($settings->get('logo')),
            'favicon_url' => $this->publicDiskUrl($settings->get('favicon')) ?: '/favicon.ico',
        ];
    }

    public function refresh(): void
    {
        $this->branding = null;
    }

    private function rawValue(mixed $value): mixed
    {
        return is_array($value) ? ($value['raw'] ?? reset($value)) : $value;
    }

    private function clean(mixed $value): ?string
    {
        $value = is_string($value) ? trim($value) : null;

        return $value !== '' ? $value : null;
    }

    private function publicDiskUrl(mixed $path): ?string
    {
        $path = $this->clean($path);

        if (! $path) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::disk('public')->exists($path)
            ? '/storage/'.ltrim($path, '/')
            : null;
    }
}
