<x-app-layout>
    <x-page-header title="Edit Attendance" description="Update the details of this attendance record." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.index') }}" wire:navigate>Attendance Records</a>
            <span class="breadcrumb-separator">/</span>
            <span>Edit</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('attendances.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id', $attendance->organization_id) == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="employee_id" class="label">Employee <span class="text-red-500">*</span></label>
                        <select id="employee_id" name="employee_id" class="select-field" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="date" class="label">Date <span class="text-red-500">*</span></label>
                        <input id="date" name="date" type="date" class="input-field" value="{{ old('date', $attendance->date) }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="clock_in" class="label">Clock In <span class="text-red-500">*</span></label>
                            <input id="clock_in" name="clock_in" type="time" class="input-field" value="{{ old('clock_in', $attendance->clock_in) }}" required />
                            <x-input-error :messages="$errors->get('clock_in')" class="mt-2" />
                        </div>
                        <div>
                            <label for="clock_out" class="label">Clock Out</label>
                            <input id="clock_out" name="clock_out" type="time" class="input-field" value="{{ old('clock_out', $attendance->clock_out) }}" />
                            <x-input-error :messages="$errors->get('clock_out')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="status" class="label">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="select-field" required>
                            <option value="">Select Status</option>
                            <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="half-day" {{ old('status', $attendance->status) == 'half-day' ? 'selected' : '' }}>Half Day</option>
                            <option value="remote" {{ old('status', $attendance->status) == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div>
                        <label for="notes" class="label">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="textarea-field">{{ old('notes', $attendance->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div>
                        <label for="is_active" class="label">Active Status</label>
                        <select id="is_active" name="is_active" class="select-field" required>
                            <option value="1" {{ old('is_active', $attendance->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $attendance->is_active) ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('attendances.show', $attendance) }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Update Attendance</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
