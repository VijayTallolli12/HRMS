<x-app-layout>
    <x-page-header title="Edit Shift" description="Update shift schedule details." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('shifts.index') }}" wire:navigate>Shifts</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('shifts.update', $shift) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id', $shift->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="name" class="label">Name <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $shift->name) }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="label">Start Time <span class="text-red-500">*</span></label>
                            <input id="start_time" name="start_time" type="time" class="input-field" value="{{ old('start_time', $shift->start_time) }}" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>
                        <div>
                            <label for="end_time" class="label">End Time <span class="text-red-500">*</span></label>
                            <input id="end_time" name="end_time" type="time" class="input-field" value="{{ old('end_time', $shift->end_time) }}" required />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="break_minutes" class="label">Break (minutes)</label>
                        <input id="break_minutes" name="break_minutes" type="number" class="input-field" value="{{ old('break_minutes', $shift->break_minutes) }}" />
                        <x-input-error :messages="$errors->get('break_minutes')" class="mt-2" />
                    </div>
                    <div>
                        <label for="description" class="label">Description</label>
                        <textarea id="description" name="description" rows="3" class="textarea-field">{{ old('description', $shift->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div>
                        <label for="is_active" class="label">Status</label>
                        <select id="is_active" name="is_active" class="select-field" required>
                            <option value="1" {{ old('is_active', $shift->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $shift->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('shifts.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Shift</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
