<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create Branch"
            icon="map-pin"
            description="Add a new branch office location."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('branches.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Branches</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('branches.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Branch Details</h3>
                        </div>
                    </div>
                    <div class="card-body space-y-5">
                        <div>
                            <x-input-label for="organization_id" value="Organization *" />
                            <select id="organization_id" name="organization_id" class="select-field mt-1.5 block w-full" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id', $selectedOrgId ?? '') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Branch Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1.5 block w-full" :value="old('phone')" />
                        </div>
                    </div>
                </div>

                {{-- Address Section --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50">
                                <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Address</h3>
                        </div>
                    </div>
                    <div class="card-body space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <x-input-label for="address[street]" value="Street" />
                                <x-text-input id="address[street]" name="address[street]" type="text" class="mt-1.5 block w-full" :value="old('address.street')" />
                            </div>
                            <div>
                                <x-input-label for="address[city]" value="City" />
                                <x-text-input id="address[city]" name="address[city]" type="text" class="mt-1.5 block w-full" :value="old('address.city')" />
                            </div>
                            <div>
                                <x-input-label for="address[state]" value="State" />
                                <x-text-input id="address[state]" name="address[state]" type="text" class="mt-1.5 block w-full" :value="old('address.state')" />
                            </div>
                            <div>
                                <x-input-label for="address[country]" value="Country" />
                                <x-text-input id="address[country]" name="address[country]" type="text" class="mt-1.5 block w-full" :value="old('address.country')" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('branches.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Branch
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
