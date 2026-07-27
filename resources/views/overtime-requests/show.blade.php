<x-app-layout>
    <x-page-header title="Overtime Request Details" description="View complete information for this overtime request." icon="clock-solid">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('overtime-requests.index') }}" wire:navigate>Overtime Requests</a>
            <span class="breadcrumb-separator">/</span>
            <span>Details</span>
        </x-slot>
        <x-slot name="actions">
            @can('update-overtime-request')
                <a href="{{ route('overtime-requests.edit', $overtimeRequest) }}" class="btn-secondary" wire:navigate>
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
                    <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-section font-semibold text-gray-900">Request Information</h3>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Employee</dt>
                    <dd class="text-body font-semibold text-gray-900">{{ $overtimeRequest->employee->first_name }} {{ $overtimeRequest->employee->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Date</dt>
                    <dd class="text-body text-gray-900">{{ $overtimeRequest->date }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Hours</dt>
                    <dd class="text-body font-semibold text-primary-600">{{ $overtimeRequest->hours }}h</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Status</dt>
                    <dd><x-status-badge :status="$overtimeRequest->status" /></dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Requested By</dt>
                    <dd class="text-body text-gray-900">{{ $overtimeRequest->requester->name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Approved By</dt>
                    <dd class="text-body text-gray-900">{{ $overtimeRequest->approver->name ?? '-' }}</dd>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <dt class="text-caption text-gray-500 mb-1">Reason</dt>
                    <dd class="text-body text-gray-900">{{ $overtimeRequest->reason }}</dd>
                </div>
                @if($overtimeRequest->rejection_reason)
                <div class="md:col-span-2 lg:col-span-3 p-4 bg-red-50 rounded-card border border-red-100">
                    <dt class="text-caption text-red-600 mb-1">Rejection Reason</dt>
                    <dd class="text-body text-red-800">{{ $overtimeRequest->rejection_reason }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</x-app-layout>
