<x-app-layout>
    <x-page-header title="Correct Attendance" icon="pencil-square" :breadcrumb="[
        ['label' => 'Attendance Corrections', 'route' => 'attendances.corrections.index'],
        ['label' => $attendance->employee->first_name . ' ' . $attendance->employee->last_name . ' — ' . $attendance->date->format('M d, Y')],
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form action="{{ route('attendances.corrections.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Attendance Details</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Clock In</label>
                            <input type="time" name="clock_in" value="{{ old('clock_in', $attendance->clock_in) }}" class="input-field" />
                            @error('clock_in') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">Clock Out</label>
                            <input type="time" name="clock_out" value="{{ old('clock_out', $attendance->clock_out) }}" class="input-field" />
                            @error('clock_out') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">Status <span class="text-rose-500">*</span></label>
                            <select name="status" class="select-field" required>
                                @foreach(['present', 'absent', 'late', 'half-day', 'remote'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $attendance->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="label">Reason for Correction <span class="text-rose-500">*</span></label>
                        <textarea name="reason" rows="3" class="input-field" required placeholder="Explain why this correction is needed...">{{ old('reason') }}</textarea>
                        @error('reason') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="btn-primary" onclick="return confirm('Save this correction? An adjustment record will be created.')">
                            <x-heroicon name="check" class="w-4 h-4" />
                            Save Correction
                        </button>
                        <a href="{{ route('attendances.corrections.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Employee Info --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Employee Info</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-gray-500">Name</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Employee ID</dt>
                    <dd class="text-sm text-gray-900">{{ $attendance->employee->employee_id }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Branch</dt>
                    <dd class="text-sm text-gray-900">{{ $attendance->employee->branch->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Department</dt>
                    <dd class="text-sm text-gray-900">{{ $attendance->employee->department->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Current Hours Worked</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ number_format($attendance->hours_worked, 2) }}h</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Source</dt>
                    <dd class="text-sm text-gray-900 capitalize">{{ $attendance->source ?? 'Manual' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>
