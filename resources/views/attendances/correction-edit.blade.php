<x-app-layout>
    <x-page-header title="Correct Attendance" icon="pencil-square">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.corrections.index') }}" wire:navigate>Corrections</a>
            <span class="breadcrumb-separator">/</span>
            <span>{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }} — {{ $attendance->date->format('M d, Y') }}</span>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form action="{{ route('attendances.corrections.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-body font-semibold text-gray-900">Attendance Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Clock In</label>
                                    <input type="time" name="clock_in" value="{{ old('clock_in', $attendance->clock_in) }}" class="input-field" />
                                    @error('clock_in') <p class="mt-1 text-caption text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Clock Out</label>
                                    <input type="time" name="clock_out" value="{{ old('clock_out', $attendance->clock_out) }}" class="input-field" />
                                    @error('clock_out') <p class="mt-1 text-caption text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Status <span class="text-red-500">*</span></label>
                                    <select name="status" class="select-field" required>
                                        @foreach(['present', 'absent', 'late', 'half-day', 'remote'] as $s)
                                            <option value="{{ $s }}" {{ old('status', $attendance->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <p class="mt-1 text-caption text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="label">Reason for Correction <span class="text-red-500">*</span></label>
                                <textarea name="reason" rows="3" class="textarea-field" required placeholder="Explain why this correction is needed...">{{ old('reason') }}</textarea>
                                @error('reason') <p class="mt-1 text-caption text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-100">
                            <button type="submit" class="btn-primary" onclick="return confirm('Save this correction? An adjustment record will be created.')">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Save Correction
                            </button>
                            <a href="{{ route('attendances.corrections.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="text-body font-semibold text-gray-900">Employee Info</h3>
            </div>
            <div class="card-body">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Name</dt>
                        <dd class="text-body font-medium text-gray-900">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Employee ID</dt>
                        <dd class="text-body text-gray-900">{{ $attendance->employee->employee_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Branch</dt>
                        <dd class="text-body text-gray-900">{{ $attendance->employee->branch->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Department</dt>
                        <dd class="text-body text-gray-900">{{ $attendance->employee->department->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Current Hours Worked</dt>
                        <dd class="text-body font-medium text-gray-900">{{ number_format($attendance->hours_worked, 2) }}h</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Source</dt>
                        <dd class="text-body text-gray-900 capitalize">{{ $attendance->source ?? 'Manual' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
