<x-app-layout>
    <x-page-header title="Resolve Missing Punch" description="Review and resolve this missing clock record." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('missing-punches.index') }}" wire:navigate>Missing Punches</a>
            <span class="breadcrumb-separator">/</span>
            <span>{{ $missingPunch->employee->first_name }} {{ $missingPunch->employee->last_name }}</span>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-card bg-amber-50">
                            <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
                        </div>
                        <h3 class="text-section font-semibold text-gray-900">Missing Punch Details</h3>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <dt class="text-caption text-gray-500 mb-1">Employee</dt>
                            <dd class="text-body font-semibold text-gray-900">{{ $missingPunch->employee->first_name }} {{ $missingPunch->employee->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-caption text-gray-500 mb-1">Date</dt>
                            <dd class="text-body text-gray-900">{{ $missingPunch->date instanceof \Carbon\Carbon ? $missingPunch->date->format('l, F j, Y') : $missingPunch->date }}</dd>
                        </div>
                        <div>
                            <dt class="text-caption text-gray-500 mb-1">Missing Type</dt>
                            <dd>
                                <span class="badge-{{ $missingPunch->punch_type === 'missing_in' ? 'danger' : 'warning' }}">
                                    {{ $missingPunch->punch_type === 'missing_in' ? 'Missing Clock In' : 'Missing Clock Out' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-caption text-gray-500 mb-1">Detected By</dt>
                            <dd class="text-body text-gray-900 capitalize">{{ $missingPunch->detected_by }}</dd>
                        </div>
                        @if($attendance)
                        <div class="md:col-span-2 p-4 bg-gray-50 rounded-card border border-gray-100">
                            <dt class="text-caption text-gray-500 mb-1">Existing Attendance Record</dt>
                            <dd class="text-body text-gray-700">
                                Clock In: <strong>{{ $attendance->clock_in ?: 'None' }}</strong> ·
                                Clock Out: <strong>{{ $attendance->clock_out ?: 'None' }}</strong> ·
                                Status: <strong>{{ ucfirst($attendance->status) }}</strong>
                            </dd>
                        </div>
                        @endif
                    </dl>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="text-body font-semibold text-gray-900 mb-3">Resolve</h4>
                        <form action="{{ route('missing-punches.resolve', $missingPunch) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="label">Correct Time <span class="text-red-500">*</span></label>
                                    <input type="time" name="correct_time" class="input-field" required
                                        value="{{ $missingPunch->punch_type === 'missing_in' ? ($attendance->clock_in ?? '09:00') : ($attendance->clock_out ?? '18:00') }}" />
                                </div>
                                <div>
                                    <label class="label">Notes</label>
                                    <textarea name="resolution_notes" rows="2" class="textarea-field" placeholder="Optional notes..."></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                                <a href="{{ route('missing-punches.index') }}" class="btn-ghost" wire:navigate>Cancel</a>
                                <form action="{{ route('missing-punches.dismiss', $missingPunch) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-secondary">Dismiss</button>
                                </form>
                                <button type="submit" class="btn-primary">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    Resolve
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="text-body font-semibold text-gray-900">Employee Info</h3>
            </div>
            <div class="card-body">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Employee ID</dt>
                        <dd class="text-body font-medium text-gray-900">{{ $missingPunch->employee->employee_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Branch</dt>
                        <dd class="text-body text-gray-900">{{ $missingPunch->employee->branch->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Department</dt>
                        <dd class="text-body text-gray-900">{{ $missingPunch->employee->department->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
