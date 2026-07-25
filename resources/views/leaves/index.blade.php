<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="calendar" class="w-5 h-5 text-indigo-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Leaves</h1>
            </div>
            @can('create-leave')
                <a href="{{ route('leaves.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    <x-heroicon name="plus" class="w-4 h-4" />
                    Request Leave
                </a>
            @endcan
        </div>
    </x-slot>

    @php
        $pendingCount = \App\Models\Leave::pending()->count();
        $approvedCount = \App\Models\Leave::where('status', 'approved')->count();
        $rejectedCount = \App\Models\Leave::where('status', 'rejected')->count();
    @endphp

    <div class="space-y-6" x-data="{ showReject: false, leaveId: null }">
        {{-- Stats Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card value="{{ $pendingCount }}" label="Pending" icon="clock" color="amber" />
            <x-stat-card value="{{ $approvedCount }}" label="Approved" icon="check-circle" color="green" />
            <x-stat-card value="{{ $rejectedCount }}" label="Rejected" icon="x-circle" color="red" />
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <form action="{{ route('leaves.index') }}" method="GET" class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <x-search-input name="search" value="{{ request('search') }}" placeholder="Search by employee name..." />
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <x-primary-button type="submit">Filter</x-primary-button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            @if ($leaves->isEmpty())
                <x-empty-state title="No leave records found" description="Get started by requesting your first leave.">
                    @can('create-leave')
                        <a href="{{ route('leaves.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Request Leave</a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($leaves as $leave)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($leave->leave_type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->days }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$leave->status" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if ($leave->status === 'pending' && auth()->user()->can('update', $leave))
                                            <form action="{{ route('leaves.approve', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-md hover:bg-emerald-100 transition">
                                                    <x-heroicon name="check-circle" class="w-3.5 h-3.5" />
                                                    Approve
                                                </button>
                                            </form>
                                            <button @click="showReject = true; leaveId = {{ $leave->id }}" type="button"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 rounded-md hover:bg-rose-100 transition">
                                                <x-heroicon name="x-circle" class="w-3.5 h-3.5" />
                                                Reject
                                            </button>
                                        @endif
                                        <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>

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
                    <form :action="'{{ url('leaves') }}/' + leaveId + '/reject'" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason</label>
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
    </div>
</x-app-layout>
