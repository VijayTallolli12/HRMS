<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create Organization"
            icon="building-storefront"
            description="Add a new organization entity to your HRMS."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('organizations.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Organizations</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('organizations.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Organization Details</h3>
                        </div>
                    </div>
                    <div class="card-body space-y-5">
                        <div>
                            <x-input-label for="name" value="Organization Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="legal_name" value="Legal Name" />
                                <x-text-input id="legal_name" name="legal_name" type="text" class="mt-1.5 block w-full" :value="old('legal_name')" />
                                <x-input-error :messages="$errors->get('legal_name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="tax_id" value="Tax ID" />
                                <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1.5 block w-full" :value="old('tax_id')" />
                                <x-input-error :messages="$errors->get('tax_id')" class="mt-1.5" />
                            </div>
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
                            <div>
                                <x-input-label for="address[zip]" value="Zip Code" />
                                <x-text-input id="address[zip]" name="address[zip]" type="text" class="mt-1.5 block w-full" :value="old('address.zip')" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('organizations.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Organization
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
