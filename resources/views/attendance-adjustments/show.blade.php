<x-app-layout>
    <x-page-header title="Attendance Adjustment Details" description="View complete information for this adjustment request." icon="adjustments-horizontal">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendance-adjustments.index') }}" wire:navigate>Attendance Adjustments</a>
            <span class="breadcrumb-separator">/</span>
            <span>Details</span>
        </x-slot>
        <x-slot name="actions">
            @can('update-attendance-adjustment')
                <a href="{{ route('attendance-adjustments.edit', $adjustment) }}" class="btn-secondary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Edit
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-card bg-primary-50">
                    <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                </div>
                <h3 class="text-section font-semibold text-gray-900">Adjustment Information</h3>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Employee</dt>
                    <dd class="text-body font-semibold text-gray-900">{{ $adjustment->employee->first_name }} {{ $adjustment->employee->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Attendance Date</dt>
                    <dd class="text-body text-gray-900">{{ $adjustment->attendance->date }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Status</dt>
                    <dd><x-status-badge :status="$adjustment->status" /></dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">New Clock In</dt>
                    <dd class="text-body font-semibold text-primary-600">{{ $adjustment->new_clock_in }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">New Clock Out</dt>
                    <dd class="text-body font-semibold text-primary-600">{{ $adjustment->new_clock_out }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">New Status</dt>
                    <dd class="text-body text-gray-900">{{ $adjustment->new_status }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Requested By</dt>
                    <dd class="text-body text-gray-900">{{ $adjustment->requester->name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Approved By</dt>
                    <dd class="text-body text-gray-900">{{ $adjustment->approver->name ?? '-' }}</dd>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <dt class="text-caption text-gray-500 mb-1">Reason</dt>
                    <dd class="text-body text-gray-900">{{ $adjustment->reason }}</dd>
                </div>
                @if($adjustment->rejection_reason)
                <div class="md:col-span-2 lg:col-span-3 p-4 bg-red-50 rounded-card border border-red-100">
                    <dt class="text-caption text-red-600 mb-1">Rejection Reason</dt>
                    <dd class="text-body text-red-800">{{ $adjustment->rejection_reason }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</x-app-layout>
