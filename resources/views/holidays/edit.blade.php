<x-app-layout>
    <x-page-header title="Edit Holiday" description="Update holiday details." icon="cake">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('holidays.index') }}" wire:navigate>Holidays</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('holidays.update', $holiday) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id', $holiday->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $holiday->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="label">Date <span class="text-red-500">*</span></label>
                            <input id="date" name="date" type="date" class="input-field" value="{{ old('date', $holiday->date) }}" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div>
                            <label for="type" class="label">Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="select-field" required>
                                <option value="">Select Type</option>
                                <option value="public" {{ old('type', $holiday->type) == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="optional" {{ old('type', $holiday->type) == 'optional' ? 'selected' : '' }}>Optional</option>
                                <option value="company" {{ old('type', $holiday->type) == 'company' ? 'selected' : '' }}>Company</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description', $holiday->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div>
                        <label for="is_active" class="label">Status</label>
                        <select id="is_active" name="is_active" class="select-field" required>
                            <option value="1" {{ old('is_active', $holiday->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $holiday->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('holidays.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Holiday</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
