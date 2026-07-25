<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="calendar" class="w-5 h-5 text-indigo-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Leave Details</h1>
            </div>
            <div class="flex items-center gap-3">
                @can('update', $leave)
                    <a href="{{ route('leaves.edit', $leave) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        <x-heroicon name="pencil-square" class="w-4 h-4" />
                        Edit
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ showReject: false }">
        {{-- Leave Details Card --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="document-text" class="w-5 h-5 text-indigo-600" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Leave Information</h3>
            </div>
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Employee</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Leave Type</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($leave->leave_type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-badge :status="$leave->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->start_date->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->end_date->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Days</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->days }}</dd>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <dt class="text-sm font-medium text-gray-500">Reason</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->reason ?? '-' }}</dd>
                </div>
                @if ($leave->rejection_reason)
                    <div class="md:col-span-2 lg:col-span-3">
                        <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                        <dd class="mt-1 text-sm text-rose-600">{{ $leave->rejection_reason }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->creator->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $leave->created_at->format('M d, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

        {{-- Approve/Reject Actions (only for pending leaves) --}}
        @if ($leave->status === 'pending' && auth()->user()->can('update', $leave))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-amber-50">
                        <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-amber-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Actions Required</h3>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('leaves.approve', $leave) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-emerald-500 transition">
                            <x-heroicon name="check-circle" class="w-5 h-5" />
                            Approve
                        </button>
                    </form>
                    <button @click="showReject = true" type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-rose-500 transition">
                        <x-heroicon name="x-circle" class="w-5 h-5" />
                        Reject
                    </button>
                </div>
            </div>
        @endif

        {{-- Reject Modal --}}
        <div x-show="showReject" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50" @click="showReject = false"></div>
                <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-6" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Reject Leave</h3>
                        <button @click="showReject = false" class="text-gray-400 hover:text-gray-600">
                            <x-heroicon name="x-mark" class="w-5 h-5" />
                        </button>
                    </div>
                    <form action="{{ route('leaves.reject', $leave) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Provide a reason for rejecting this leave..."></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showReject = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-rose-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-500">Reject Leave</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('leaves.index') }}" class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center gap-1">
                <x-heroicon name="arrow-left" class="w-4 h-4" />
                Back to Leaves
            </a>
        </div>
    </div>
</x-app-layout>
