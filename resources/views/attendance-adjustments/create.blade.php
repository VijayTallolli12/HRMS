<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Attendance Adjustment</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('attendance-adjustments.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="organization_id" value="Organization" />
                            <select id="organization_id" name="organization_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="attendance_id" value="Attendance" />
                            <select id="attendance_id" name="attendance_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Attendance</option>
                                @foreach ($attendances as $attendance)
                                    <option value="{{ $attendance->id }}" {{ old('attendance_id') == $attendance->id ? 'selected' : '' }}>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }} - {{ $attendance->date }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('attendance_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="employee_id" value="Employee" />
                            <select id="employee_id" name="employee_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reason" value="Reason" />
                            <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="new_clock_in" value="New Clock In" />
                            <x-text-input id="new_clock_in" name="new_clock_in" type="time" class="mt-1 block w-full" :value="old('new_clock_in')" required />
                            <x-input-error :messages="$errors->get('new_clock_in')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="new_clock_out" value="New Clock Out" />
                            <x-text-input id="new_clock_out" name="new_clock_out" type="time" class="mt-1 block w-full" :value="old('new_clock_out')" required />
                            <x-input-error :messages="$errors->get('new_clock_out')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="new_status" value="New Status" />
                            <select id="new_status" name="new_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Status</option>
                                <option value="present" {{ old('new_status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('new_status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ old('new_status') == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="half-day" {{ old('new_status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                                <option value="remote" {{ old('new_status') == 'remote' ? 'selected' : '' }}>Remote</option>
                            </select>
                            <x-input-error :messages="$errors->get('new_status')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('attendance-adjustments.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Adjustment</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
