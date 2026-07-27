<x-app-layout>
    <x-page-header title="Create Attendance" description="Manually record a new attendance entry for an employee." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.index') }}" wire:navigate>Attendance Records</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('attendances.store') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="organization_id" class="label">Organization <span class="text-red-500">*</span></label>
                        <select id="organization_id" name="organization_id" class="select-field" required>
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="employee_id" class="label">Employee <span class="text-red-500">*</span></label>
                        <select id="employee_id" name="employee_id" class="select-field" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                    </div>
                    <div>
                        <label for="date" class="label">Date <span class="text-red-500">*</span></label>
                        <input id="date" name="date" type="date" class="input-field" value="{{ old('date') }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="clock_in" class="label">Clock In <span class="text-red-500">*</span></label>
                            <input id="clock_in" name="clock_in" type="time" class="input-field" value="{{ old('clock_in') }}" required />
                            <x-input-error :messages="$errors->get('clock_in')" class="mt-2" />
                        </div>
                        <div>
                            <label for="clock_out" class="label">Clock Out</label>
                            <input id="clock_out" name="clock_out" type="time" class="input-field" value="{{ old('clock_out') }}" />
                            <x-input-error :messages="$errors->get('clock_out')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="status" class="label">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="select-field" required>
                            <option value="">Select Status</option>
                            <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="half-day" {{ old('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                            <option value="remote" {{ old('status') == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                    <div>
                        <label for="notes" class="label">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="textarea-field">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('attendances.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Attendance</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
