<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $organization->name }}"
            icon="pencil-square"
            description="Update organization information."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('organizations.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Organizations</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $organization->name }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('organizations.update', $organization) }}" method="POST">
            @csrf
            @method('PUT')
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
                            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $organization->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="legal_name" value="Legal Name" />
                                <x-text-input id="legal_name" name="legal_name" type="text" class="mt-1.5 block w-full" :value="old('legal_name', $organization->legal_name)" />
                            </div>
                            <div>
                                <x-input-label for="tax_id" value="Tax ID" />
                                <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1.5 block w-full" :value="old('tax_id', $organization->tax_id)" />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="status" value="Status *" />
                            <select id="status" name="status" class="select-field mt-1.5 block w-full">
                                <option value="active" {{ old('status', $organization->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $organization->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('organizations.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Organization
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
