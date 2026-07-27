<x-app-layout>
    <x-page-header title="Edit Shift Assignment" description="Update shift assignment details." icon="user-group">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('shift-assignments.index') }}" wire:navigate>Shift Assignments</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('shift-assignments.update', $shiftAssignment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="employee_id" class="label">Employee <span class="text-red-500">*</span></label>
                        <select id="employee_id" name="employee_id" class="select-field" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $shiftAssignment->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="shift_id" class="label">Shift <span class="text-red-500">*</span></label>
                        <select id="shift_id" name="shift_id" class="select-field" required>
                            <option value="">Select Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ old('shift_id', $shiftAssignment->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="effective_from" class="label">Effective From <span class="text-red-500">*</span></label>
                            <input id="effective_from" name="effective_from" type="date" class="input-field" value="{{ old('effective_from', $shiftAssignment->effective_from) }}" required />
                            <x-input-error :messages="$errors->get('effective_from')" class="mt-2" />
                        </div>
                        <div>
                            <label for="effective_to" class="label">Effective To</label>
                            <input id="effective_to" name="effective_to" type="date" class="input-field" value="{{ old('effective_to', $shiftAssignment->effective_to) }}" />
                            <x-input-error :messages="$errors->get('effective_to')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="notes" class="label">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="textarea-field">{{ old('notes', $shiftAssignment->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div>
                        <label for="is_active" class="label">Status</label>
                        <select id="is_active" name="is_active" class="select-field" required>
                            <option value="1" {{ old('is_active', $shiftAssignment->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $shiftAssignment->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('shift-assignments.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Assignment</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
