<x-app-layout>
    <x-page-header title="Settings" icon="heroicon-o-cog-6-tooth" />

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex items-center gap-2">
                <x-heroicon name="heroicon-o-check-circle" class="h-5 w-5 text-green-600" />
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="card" x-data="{ activeTab: 'general' }">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px px-6 gap-1">
                <button
                    type="button"
                    x-on:click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                >
                    General
                </button>
                <button
                    type="button"
                    x-on:click="activeTab = 'branding'"
                    :class="activeTab === 'branding' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                >
                    Branding
                </button>
                <button
                    type="button"
                    x-on:click="activeTab = 'email'"
                    :class="activeTab === 'email' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                >
                    Email
                </button>
                <button
                    type="button"
                    x-on:click="activeTab = 'system'"
                    :class="activeTab === 'system' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                >
                    System
                </button>
            </nav>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6">
                {{-- General Tab --}}
                <div x-show="activeTab === 'general'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="company_name" value="Company Name" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $settings['company_name'] ?? '')" />
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="address" value="Address" />
                            <textarea id="address" name="address" rows="3" class="input-field mt-1 block w-full">{{ old('address', $settings['address'] ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $settings['phone'] ?? '')" />
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $settings['email'] ?? '')" />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="website" value="Website" />
                            <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $settings['website'] ?? '')" />
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Branding Tab --}}
                <div x-show="activeTab === 'branding'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="app_title" value="App Title" />
                            <x-text-input id="app_title" name="app_title" type="text" class="mt-1 block w-full" :value="old('app_title', $settings['app_title'] ?? '')" />
                            @error('app_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="primary_color" value="Primary Color" />
                            <input id="primary_color" name="primary_color" type="color" class="mt-1 h-10 w-20 rounded border border-gray-300" value="{{ old('primary_color', $settings['primary_color'] ?? '#4f46e5') }}" />
                            @error('primary_color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="logo" value="Logo" />
                            <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            @if(!empty($settings['logo']))
                                <p class="mt-2 text-xs text-gray-500">Current: {{ $settings['logo'] }}</p>
                            @endif
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="favicon" value="Favicon" />
                            <input id="favicon" name="favicon" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            @if(!empty($settings['favicon']))
                                <p class="mt-2 text-xs text-gray-500">Current: {{ $settings['favicon'] }}</p>
                            @endif
                            @error('favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Email Tab --}}
                <div x-show="activeTab === 'email'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="smtp_host" value="SMTP Host" />
                            <x-text-input id="smtp_host" name="smtp_host" type="text" class="mt-1 block w-full" :value="old('smtp_host', $settings['smtp_host'] ?? '')" />
                            @error('smtp_host')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="smtp_port" value="SMTP Port" />
                            <x-text-input id="smtp_port" name="smtp_port" type="number" class="mt-1 block w-full" :value="old('smtp_port', $settings['smtp_port'] ?? '')" />
                            @error('smtp_port')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="from_address" value="From Address" />
                            <x-text-input id="from_address" name="from_address" type="email" class="mt-1 block w-full" :value="old('from_address', $settings['from_address'] ?? '')" />
                            @error('from_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="from_name" value="From Name" />
                            <x-text-input id="from_name" name="from_name" type="text" class="mt-1 block w-full" :value="old('from_name', $settings['from_name'] ?? '')" />
                            @error('from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="encryption" value="Encryption" />
                            <select id="encryption" name="encryption" class="select-field mt-1 block w-full">
                                <option value="tls" {{ old('encryption', $settings['encryption'] ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('encryption', $settings['encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('encryption')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- System Tab --}}
                <div x-show="activeTab === 'system'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="date_format" value="Date Format" />
                            <select id="date_format" name="date_format" class="select-field mt-1 block w-full">
                                <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? 'Y-m-d') === 'Y-m-d' ? 'selected' : '' }}>Y-m-d</option>
                                <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' }}>d/m/Y</option>
                                <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>m/d/Y</option>
                            </select>
                            @error('date_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="timezone" value="Timezone" />
                            <select id="timezone" name="timezone" class="select-field mt-1 block w-full">
                                @php
                                    $timezones = [
                                        'UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles',
                                        'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Asia/Dubai', 'Asia/Kolkata',
                                        'Asia/Shanghai', 'Asia/Tokyo', 'Australia/Sydney', 'Pacific/Auckland',
                                    ];
                                @endphp
                                @foreach($timezones as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $settings['timezone'] ?? 'UTC') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-input-label for="currency" value="Currency" />
                            <select id="currency" name="currency" class="select-field mt-1 block w-full">
                                <option value="USD" {{ old('currency', $settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ old('currency', $settings['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency', $settings['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="INR" {{ old('currency', $settings['currency'] ?? '') === 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end">
                <x-primary-button type="submit">Save Settings</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
