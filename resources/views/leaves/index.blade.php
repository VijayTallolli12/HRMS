<x-app-layout>
    <x-page-header title="Leave Management" description="Track and manage employee leave requests, approvals, and balances." icon="calendar">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Leave Management</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-leave')
                <a href="{{ route('leaves.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Request Leave
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    @php
        $pendingCount = \App\Models\Leave::pending()->count();
        $approvedCount = \App\Models\Leave::where('status', 'approved')->count();
        $rejectedCount = \App\Models\Leave::where('status', 'rejected')->count();
    @endphp

    <div class="space-y-5" x-data="{ showReject: false, leaveId: null }">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card value="{{ $pendingCount }}" label="Pending" icon="clock" color="amber" />
            <x-stat-card value="{{ $approvedCount }}" label="Approved" icon="check-circle" color="green" />
            <x-stat-card value="{{ $rejectedCount }}" label="Rejected" icon="x-circle" color="red" />
        </div>

        <div class="filter-bar">
            <form action="{{ route('leaves.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search by employee name..." />
                </div>
                <div class="filter-group min-w-[160px]">
                    <label class="filter-label">Status</label>
                    <select name="status" class="select-field">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="filter-group min-w-[140px]">
                    <label class="filter-label">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
                </div>
                <div class="filter-group min-w-[140px]">
                    <label class="filter-label">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($leaves->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    <p class="text-title text-gray-900 mb-1">No leave records found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by requesting your first leave.</p>
                    @can('create-leave')
                        <a href="{{ route('leaves.create') }}" class="btn-primary" wire:navigate>Request Leave</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td>
                                        <a href="{{ route('leaves.show', $leave) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>
                                            {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ ucfirst($leave->leave_type) }}</td>
                                    <td>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->days }}</td>
                                    <td><x-status-badge :status="$leave->status" /></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @if ($leave->status === 'pending' && auth()->user()->can('update', $leave))
                                                <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn-success text-xs px-2.5 py-1">
                                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        Approve
                                                    </button>
                                                </form>
                                                <button @click="showReject = true; leaveId = {{ $leave->id }}" type="button" class="btn-danger text-xs px-2.5 py-1">
                                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    Reject
                                                </button>
                                            @endif
                                            <a href="{{ route('leaves.show', $leave) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>

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
                    <form :action="'{{ url('leaves') }}/' + leaveId + '/reject'" method="POST" class="space-y-4">
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
