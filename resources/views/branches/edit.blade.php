<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $branch->name }}"
            icon="pencil-square"
            description="Update branch office information."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('branches.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Branches</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $branch->name }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <form action="{{ route('branches.update', $branch) }}" method="POST">
            @csrf
            @method('PUT')
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
                            <x-input-label for="name" value="Branch Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name', $branch->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1.5 block w-full" :value="old('phone', $branch->phone)" />
                        </div>
                        <div>
                            <x-input-label for="status" value="Status *" />
                            <select id="status" name="status" class="select-field mt-1.5 block w-full">
                                <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('branches.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Branch
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
