<x-app-layout>
    <x-page-header title="Create Attendance Adjustment" description="Request a correction to an existing attendance record." icon="adjustments-horizontal">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendance-adjustments.index') }}" wire:navigate>Attendance Adjustments</a>
            <span class="breadcrumb-separator">/</span>
            <span>Create</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl">
        <div class="card-body">
            <form action="{{ route('attendance-adjustments.store') }}" method="POST">
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
                        <label for="attendance_id" class="label">Attendance <span class="text-red-500">*</span></label>
                        <select id="attendance_id" name="attendance_id" class="select-field" required>
                            <option value="">Select Attendance</option>
                            @foreach ($attendances as $attendance)
                                <option value="{{ $attendance->id }}" {{ old('attendance_id') == $attendance->id ? 'selected' : '' }}>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }} - {{ $attendance->date }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('attendance_id')" class="mt-2" />
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
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="new_clock_in" class="label">New Clock In <span class="text-red-500">*</span></label>
                            <input id="new_clock_in" name="new_clock_in" type="time" class="input-field" value="{{ old('new_clock_in') }}" required />
                            <x-input-error :messages="$errors->get('new_clock_in')" class="mt-2" />
                        </div>
                        <div>
                            <label for="new_clock_out" class="label">New Clock Out <span class="text-red-500">*</span></label>
                            <input id="new_clock_out" name="new_clock_out" type="time" class="input-field" value="{{ old('new_clock_out') }}" required />
                            <x-input-error :messages="$errors->get('new_clock_out')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <label for="new_status" class="label">New Status <span class="text-red-500">*</span></label>
                        <select id="new_status" name="new_status" class="select-field" required>
                            <option value="">Select Status</option>
                            <option value="present" {{ old('new_status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('new_status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ old('new_status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="half-day" {{ old('new_status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                            <option value="remote" {{ old('new_status') == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        <x-input-error :messages="$errors->get('new_status')" class="mt-2" />
                    </div>
                    <div>
                        <label for="reason" class="label">Reason <span class="text-red-500">*</span></label>
                        <textarea id="reason" name="reason" rows="3" class="textarea-field" required>{{ old('reason') }}</textarea>
                        <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('attendance-adjustments.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        <x-primary-button type="submit">Create Adjustment</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
