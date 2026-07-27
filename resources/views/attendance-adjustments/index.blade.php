<x-app-layout>
    <x-page-header title="Attendance Adjustments" description="Manage and approve attendance correction requests." icon="adjustments-horizontal">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Attendance Adjustments</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-attendance-adjustment')
                <a href="{{ route('attendance-adjustments.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Adjustment
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('attendance-adjustments.index') }}" method="GET" class="filter-bar-inner">
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
                    </select>
                </div>
                <div class="filter-group min-w-[180px]">
                    <label class="filter-label">Organization</label>
                    <select name="organization_id" class="select-field">
                        <option value="">All Organizations</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($adjustments->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                    <p class="text-title text-gray-900 mb-1">No adjustments found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first attendance adjustment.</p>
                    @can('create-attendance-adjustment')
                        <a href="{{ route('attendance-adjustments.create') }}" class="btn-primary" wire:navigate>Create Adjustment</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Attendance Date</th>
                                <th>Reason</th>
                                <th>New Clock In</th>
                                <th>New Clock Out</th>
                                <th>New Status</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adjustments as $adjustment)
                                <tr>
                                    <td>
                                        <a href="{{ route('attendance-adjustments.show', $adjustment) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $adjustment->employee->first_name }} {{ $adjustment->employee->last_name }}</a>
                                    </td>
                                    <td>{{ $adjustment->attendance->date }}</td>
                                    <td class="max-w-[200px] truncate">{{ $adjustment->reason }}</td>
                                    <td>{{ $adjustment->new_clock_in }}</td>
                                    <td>{{ $adjustment->new_clock_out }}</td>
                                    <td>{{ $adjustment->new_status }}</td>
                                    <td><x-status-badge :status="$adjustment->status" /></td>
                                    <td>{{ $adjustment->requester->name }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-attendance-adjustment')
                                                <a href="{{ route('attendance-adjustments.show', $adjustment) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-attendance-adjustment')
                                                <a href="{{ route('attendance-adjustments.edit', $adjustment) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $adjustments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
