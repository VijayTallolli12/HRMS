<x-app-layout>
    <x-page-header title="Overtime Requests" description="Manage and review employee overtime requests." icon="clock-solid">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Overtime Requests</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-overtime-request')
                <a href="{{ route('overtime-requests.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Request
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('overtime-requests.index') }}" method="GET" class="filter-bar-inner">
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
            @if ($overtimeRequests->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No overtime requests found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first overtime request.</p>
                    @can('create-overtime-request')
                        <a href="{{ route('overtime-requests.create') }}" class="btn-primary" wire:navigate>Create Request</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Hours</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Approved By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overtimeRequests as $overtimeRequest)
                                <tr>
                                    <td>
                                        <a href="{{ route('overtime-requests.show', $overtimeRequest) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $overtimeRequest->employee->first_name }} {{ $overtimeRequest->employee->last_name }}</a>
                                    </td>
                                    <td>{{ $overtimeRequest->date }}</td>
                                    <td>{{ $overtimeRequest->hours }}h</td>
                                    <td class="max-w-[200px] truncate">{{ $overtimeRequest->reason }}</td>
                                    <td><x-status-badge :status="$overtimeRequest->status" /></td>
                                    <td>{{ $overtimeRequest->requester->name }}</td>
                                    <td>{{ $overtimeRequest->approver->name ?? '-' }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-overtime-request')
                                                <a href="{{ route('overtime-requests.show', $overtimeRequest) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-overtime-request')
                                                <a href="{{ route('overtime-requests.edit', $overtimeRequest) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $overtimeRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
