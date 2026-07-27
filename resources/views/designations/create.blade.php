<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create Designation"
            icon="academic-cap"
            description="Add a new job designation to your organization."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('designations.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Designations</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <form action="{{ route('designations.store') }}" method="POST">
                @csrf
                <div class="card-body space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                            <x-input-label for="department_id" value="Department *" />
                            <select id="department_id" name="department_id" class="select-field mt-1.5 block w-full" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-1.5" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="title" value="Title *" />
                        <x-text-input id="title" name="title" type="text" class="mt-1.5 block w-full" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                    </div>
                    <div>
                        <x-input-label for="level" value="Level" />
                        <x-text-input id="level" name="level" type="text" class="mt-1.5 block w-full" :value="old('level')" placeholder="e.g., L1, L2, Senior" />
                    </div>
                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="3" class="textarea-field mt-1.5 block w-full">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="card-header border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <a href="{{ route('designations.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    <x-primary-button type="submit" class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Designation
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
