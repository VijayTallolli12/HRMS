<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Overtime Request Details</h2>
            <div class="flex gap-2">
                @can('update-overtime-request')
                    <a href="{{ route('overtime-requests.edit', $overtimeRequest) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>Edit</a>
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
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->employee->first_name }} {{ $overtimeRequest->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->date }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hours</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->hours }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->reason }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$overtimeRequest->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Requested By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->requester->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->approver->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $overtimeRequest->rejection_reason ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-start">
                <a href="{{ route('overtime-requests.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Back to Overtime Requests</a>
            </div>
        </div>
    </div>
</x-app-layout>
