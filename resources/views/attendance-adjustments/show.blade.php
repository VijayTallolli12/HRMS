<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attendance Adjustment Details</h2>
            <div class="flex gap-2">
                @can('update-attendance-adjustment')
                    <a href="{{ route('attendance-adjustments.edit', $adjustment) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>Edit</a>
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
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->employee->first_name }} {{ $adjustment->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Attendance Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->attendance->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->reason }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">New Clock In</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->new_clock_in }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">New Clock Out</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->new_clock_out }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">New Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->new_status }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$adjustment->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Requested By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->requester->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->approver->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $adjustment->rejection_reason ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-start">
                <a href="{{ route('attendance-adjustments.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Back to Adjustments</a>
            </div>
        </div>
    </div>
</x-app-layout>
