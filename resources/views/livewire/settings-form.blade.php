<div class="max-w-4xl mx-auto space-y-6">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-card p-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-body font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="border-b border-gray-200">
            <nav class="tab-nav flex gap-1 px-6" role="tablist">
                <button type="button" wire:click="setActiveTab('general')" wire:key="settings-tab-general" @class(['tab-btn inline-flex items-center gap-2', 'tab-btn-active' => $activeTab === 'general', 'tab-btn-inactive' => $activeTab !== 'general']) role="tab" aria-selected="{{ $activeTab === 'general' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    General
                </button>
                <button type="button" wire:click="setActiveTab('branding')" wire:key="settings-tab-branding" @class(['tab-btn inline-flex items-center gap-2', 'tab-btn-active' => $activeTab === 'branding', 'tab-btn-inactive' => $activeTab !== 'branding']) role="tab" aria-selected="{{ $activeTab === 'branding' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
                    Branding
                </button>
                <button type="button" wire:click="setActiveTab('email')" wire:key="settings-tab-email" @class(['tab-btn inline-flex items-center gap-2', 'tab-btn-active' => $activeTab === 'email', 'tab-btn-inactive' => $activeTab !== 'email']) role="tab" aria-selected="{{ $activeTab === 'email' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    Email
                </button>
                <button type="button" wire:click="setActiveTab('system')" wire:key="settings-tab-system" @class(['tab-btn inline-flex items-center gap-2', 'tab-btn-active' => $activeTab === 'system', 'tab-btn-inactive' => $activeTab !== 'system']) role="tab" aria-selected="{{ $activeTab === 'system' ? 'true' : 'false' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" /></svg>
                    System
                </button>
            </nav>
        </div>

        <form wire:submit="save" enctype="multipart/form-data">
            <div class="p-6">
                @if($activeTab === 'general')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" wire:key="settings-panel-general">
                        <div class="md:col-span-2">
                            <x-input-label for="company_name" value="Company Name" />
                            <x-text-input wire:model="settings.company_name" id="company_name" type="text" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.company_name')" class="mt-1.5" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="address" value="Address" />
                            <textarea wire:model="settings.address" id="address" rows="3" class="textarea-field mt-1.5 block w-full"></textarea>
                            <x-input-error :messages="$errors->get('settings.address')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input wire:model="settings.phone" id="phone" type="text" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.phone')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input wire:model="settings.email" id="email" type="email" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.email')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="website" value="Website" />
                            <x-text-input wire:model="settings.website" id="website" type="url" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.website')" class="mt-1.5" />
                        </div>
                    </div>
                @endif

                @if($activeTab === 'branding')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" wire:key="settings-panel-branding">
                        <div>
                            <x-input-label for="app_title" value="App Title" />
                            <x-text-input wire:model="settings.app_title" id="app_title" type="text" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.app_title')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="primary_color" value="Primary Color" />
                            <input wire:model="settings.primary_color" id="primary_color" type="color" class="mt-1.5 h-10 w-20 rounded-input border border-gray-300 cursor-pointer" />
                            <x-input-error :messages="$errors->get('settings.primary_color')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="logoUpload" value="Logo" />
                            <input wire:model="logoUpload" id="logoUpload" type="file" accept="image/*" class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-input file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer" />
                            @if(!empty($settings['logo']))
                                <p class="mt-2 text-caption text-gray-500">Current: {{ $settings['logo'] }}</p>
                            @endif
                            <x-input-error :messages="$errors->get('logoUpload')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="faviconUpload" value="Favicon" />
                            <input wire:model="faviconUpload" id="faviconUpload" type="file" accept="image/*" class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-input file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer" />
                            @if(!empty($settings['favicon']))
                                <p class="mt-2 text-caption text-gray-500">Current: {{ $settings['favicon'] }}</p>
                            @endif
                            <x-input-error :messages="$errors->get('faviconUpload')" class="mt-1.5" />
                        </div>
                    </div>
                @endif

                @if($activeTab === 'email')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" wire:key="settings-panel-email">
                        <div>
                            <x-input-label for="smtp_host" value="SMTP Host" />
                            <x-text-input wire:model="settings.smtp_host" id="smtp_host" type="text" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.smtp_host')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="smtp_port" value="SMTP Port" />
                            <x-text-input wire:model="settings.smtp_port" id="smtp_port" type="number" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.smtp_port')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="from_address" value="From Address" />
                            <x-text-input wire:model="settings.from_address" id="from_address" type="email" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.from_address')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="from_name" value="From Name" />
                            <x-text-input wire:model="settings.from_name" id="from_name" type="text" class="mt-1.5 block w-full" />
                            <x-input-error :messages="$errors->get('settings.from_name')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="encryption" value="Encryption" />
                            <select wire:model="settings.encryption" id="encryption" class="select-field mt-1.5 block w-full">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                            </select>
                            <x-input-error :messages="$errors->get('settings.encryption')" class="mt-1.5" />
                        </div>
                    </div>
                @endif

                @if($activeTab === 'system')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" wire:key="settings-panel-system">
                        <div>
                            <x-input-label for="date_format" value="Date Format" />
                            <select wire:model="settings.date_format" id="date_format" class="select-field mt-1.5 block w-full">
                                <option value="Y-m-d">Y-m-d</option>
                                <option value="d/m/Y">d/m/Y</option>
                                <option value="m/d/Y">m/d/Y</option>
                            </select>
                            <x-input-error :messages="$errors->get('settings.date_format')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="timezone" value="Timezone" />
                            <select wire:model="settings.timezone" id="timezone" class="select-field mt-1.5 block w-full">
                                @foreach(['UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Asia/Dubai', 'Asia/Kolkata', 'Asia/Shanghai', 'Asia/Tokyo', 'Australia/Sydney', 'Pacific/Auckland'] as $tz)
                                    <option value="{{ $tz }}">{{ $tz }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('settings.timezone')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="currency" value="Currency" />
                            <select wire:model="settings.currency" id="currency" class="select-field mt-1.5 block w-full">
                                <option value="INR">INR - Indian Rupee</option>
                                <option value="USD">USD - US Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                            </select>
                            <x-input-error :messages="$errors->get('settings.currency')" class="mt-1.5" />
                        </div>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end">
                <x-primary-button type="submit" class="inline-flex items-center gap-2" wire:loading.attr="disabled" wire:target="save,logoUpload,faviconUpload">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    Save Settings
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
