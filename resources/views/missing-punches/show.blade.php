<x-app-layout>
    <x-page-header title="Resolve Missing Punch" icon="exclamation-triangle" :breadcrumb="[
        ['label' => 'Missing Punches', 'route' => 'missing-punches.index'],
        ['label' => $missingPunch->employee->first_name . ' ' . $missingPunch->employee->last_name],
    ]" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Missing Punch Details</h3>
                <dl class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <dt class="text-xs text-gray-500">Employee</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $missingPunch->employee->first_name }} {{ $missingPunch->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Date</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $missingPunch->date instanceof \Carbon\Carbon ? $missingPunch->date->format('l, F j, Y') : $missingPunch->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Missing Type</dt>
                        <dd class="text-sm font-medium">{{ $missingPunch->punch_type === 'missing_in' ? 'Missing Clock In' : 'Missing Clock Out' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Detected By</dt>
                        <dd class="text-sm font-medium capitalize">{{ $missingPunch->detected_by }}</dd>
                    </div>
                    @if($attendance)
                    <div class="col-span-2 p-3 bg-gray-50 rounded-lg">
                        <dt class="text-xs text-gray-500 mb-1">Existing Attendance Record</dt>
                        <dd class="text-sm text-gray-700">
                            Clock In: <strong>{{ $attendance->clock_in ?: 'None' }}</strong> ·
                            Clock Out: <strong>{{ $attendance->clock_out ?: 'None' }}</strong> ·
                            Status: <strong>{{ ucfirst($attendance->status) }}</strong>
                        </dd>
                    </div>
                    @endif
                </dl>

                <form action="{{ route('missing-punches.resolve', $missingPunch) }}" method="POST">
                    @csrf
                    <div class="border-t border-gray-100 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Resolve</h4>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="label">Correct Time <span class="text-rose-500">*</span></label>
                                <input type="time" name="correct_time" class="input-field" required
                                    value="{{ $missingPunch->punch_type === 'missing_in' ? ($attendance->clock_in ?? '09:00') : ($attendance->clock_out ?? '18:00') }}" />
                            </div>
                        </div>
                        <div>
                            <label class="label">Notes</label>
                            <textarea name="resolution_notes" rows="2" class="input-field" placeholder="Optional notes..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('missing-punches.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <form action="{{ route('missing-punches.dismiss', $missingPunch) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary text-gray-500">Dismiss</button>
                        </form>
                        <button type="submit" class="btn-primary">
                            <x-heroicon name="check" class="w-4 h-4" />
                            Resolve
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Employee Info</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-xs text-gray-500">Employee ID</dt>
                    <dd class="text-sm text-gray-900">{{ $missingPunch->employee->employee_id }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Branch</dt>
                    <dd class="text-sm text-gray-900">{{ $missingPunch->employee->branch->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Department</dt>
                    <dd class="text-sm text-gray-900">{{ $missingPunch->employee->department->name ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>
