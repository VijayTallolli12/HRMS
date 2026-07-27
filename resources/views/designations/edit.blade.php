<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit {{ $designation->title }}"
            icon="pencil-square"
            description="Update designation information."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('designations.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Designations</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit {{ $designation->title }}</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <form action="{{ route('designations.update', $designation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body space-y-5">
                    <div>
                        <x-input-label for="title" value="Title *" />
                        <x-text-input id="title" name="title" type="text" class="mt-1.5 block w-full" :value="old('title', $designation->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="level" value="Level" />
                        <x-text-input id="level" name="level" type="text" class="mt-1.5 block w-full" :value="old('level', $designation->level)" />
                    </div>
                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('description', $designation->description) }}</textarea>
                    </div>
                    <div>
                        <x-input-label for="status" value="Status *" />
                        <select id="status" name="status" class="select-field mt-1.5 block w-full">
                            <option value="active" {{ old('status', $designation->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $designation->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-header border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <a href="{{ route('designations.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Update Designation
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
