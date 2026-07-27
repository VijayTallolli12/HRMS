<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('view-settings');

        $settings = ApplicationSetting::orderBy('key')->get()
            ->mapWithKeys(fn ($s) => [$s->key => is_array($s->value) ? ($s->value['raw'] ?? reset($s->value)) : $s->value])
            ->toArray();

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->authorize('update-settings');

        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'app_title' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:2048',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'encryption' => 'nullable|in:tls,ssl',
            'date_format' => 'nullable|string|max:20',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:5',
        ]);

        $groups = [
            'general' => ['company_name', 'address', 'phone', 'email', 'website'],
            'branding' => ['app_title', 'primary_color', 'logo', 'favicon'],
            'email' => ['smtp_host', 'smtp_port', 'from_address', 'from_name', 'encryption'],
            'system' => ['date_format', 'timezone', 'currency'],
        ];

        foreach ($validated as $key => $value) {
            if ($value === null || $value === '') {
                ApplicationSetting::where('key', $key)->delete();

                continue;
            }

            $group = collect($groups)->keys()->first(fn ($keys) => in_array($key, $groups[$keys])) ?? 'general';
            ApplicationSetting::set($key, $value, $group);
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
