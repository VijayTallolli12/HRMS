<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attendance Details</h2>
            <div class="flex gap-2">
                @can('update-attendance')
                    <a href="{{ route('attendances.edit', $attendance) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>Edit</a>
                @endcan
            </div>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Employee</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Clock In</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->clock_in }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Clock Out</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->clock_out }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$attendance->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hours Worked</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->hours_worked }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Overtime Hours</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->overtime_hours }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Late Minutes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->late_minutes }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Early Leave Minutes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->early_leave_minutes }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->notes }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $attendance->created_at }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-start">
                <a href="{{ route('attendances.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Back to Attendances</a>
            </div>
        </div>
    </div>
</x-app-layout>
