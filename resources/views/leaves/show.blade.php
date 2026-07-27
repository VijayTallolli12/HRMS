<x-app-layout>
    <x-page-header title="Leave Details" description="View complete information for this leave request." icon="calendar">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('leaves.index') }}" wire:navigate>Leave Management</a>
            <span class="breadcrumb-separator">/</span>
            <span>Leave Details</span>
        </x-slot>
        <x-slot name="actions">
            @can('update', $leave)
                <a href="{{ route('leaves.edit', $leave) }}" class="btn-secondary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Edit
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5" x-data="{ showReject: false }">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-card bg-primary-50">
                        <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <h3 class="text-section font-semibold text-gray-900">Leave Information</h3>
                </div>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Employee</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Leave Type</dt>
                        <dd class="text-body text-gray-900">{{ ucfirst($leave->leave_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Status</dt>
                        <dd><x-status-badge :status="$leave->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Start Date</dt>
                        <dd class="text-body text-gray-900">{{ $leave->start_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">End Date</dt>
                        <dd class="text-body text-gray-900">{{ $leave->end_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Days</dt>
                        <dd class="text-body text-gray-900">{{ $leave->days }}</dd>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <dt class="text-caption text-gray-500 mb-1">Reason</dt>
                        <dd class="text-body text-gray-900">{{ $leave->reason ?? '-' }}</dd>
                    </div>
                    @if ($leave->rejection_reason)
                        <div class="md:col-span-2 lg:col-span-3 p-4 bg-red-50 rounded-card border border-red-200">
                            <dt class="text-caption font-medium text-red-700 mb-1">Rejection Reason</dt>
                            <dd class="text-body text-red-600">{{ $leave->rejection_reason }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Created By</dt>
                        <dd class="text-body text-gray-900">{{ $leave->creator->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption text-gray-500 mb-1">Created At</dt>
                        <dd class="text-body text-gray-900">{{ $leave->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if ($leave->status === 'pending' && auth()->user()->can('update', $leave))
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-card bg-amber-50">
                            <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-section font-semibold text-gray-900">Actions Required</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-success">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Approve
                            </button>
                        </form>
                        <button @click="showReject = true" type="button" class="btn-danger">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div x-show="showReject" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showReject = false"></div>
                <div class="card relative w-full max-w-md p-6 shadow-xl" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-title text-gray-900">Reject Leave</h3>
                        <button @click="showReject = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="label">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" required class="textarea-field" placeholder="Provide a reason for rejecting this leave..."></textarea>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" @click="showReject = false" class="btn-ghost">Cancel</button>
                            <button type="submit" class="btn-danger">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Reject Leave
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
