<x-app-layout>
    <x-page-header title="Late Policies" description="Configure late attendance policies including grace periods and penalties." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Late Policies</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-late-policy')
                <a href="{{ route('late-policies.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Policy
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('late-policies.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search policies..." />
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
            @if ($latePolicies->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No late policies found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first late policy.</p>
                    @can('create-late-policy')
                        <a href="{{ route('late-policies.create') }}" class="btn-primary" wire:navigate>Create Policy</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Grace Minutes</th>
                                <th>Max Late/Month</th>
                                <th>Penalty Type</th>
                                <th>Penalty Amount</th>
                                <th>Organization</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latePolicies as $latePolicy)
                                <tr>
                                    <td>
                                        <a href="{{ route('late-policies.show', $latePolicy) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $latePolicy->name }}</a>
                                    </td>
                                    <td>{{ $latePolicy->grace_minutes }}</td>
                                    <td>{{ $latePolicy->max_late_per_month }}</td>
                                    <td><span class="badge-{{ $latePolicy->penalty_type === 'deduction' ? 'danger' : ($latePolicy->penalty_type === 'suspension' ? 'warning' : 'default') }}">{{ ucfirst($latePolicy->penalty_type) }}</span></td>
                                    <td>{{ $latePolicy->penalty_amount ?: '-' }}</td>
                                    <td>{{ $latePolicy->organization->name }}</td>
                                    <td><x-status-badge :status="$latePolicy->is_active ? 'active' : 'inactive'" /></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-late-policy')
                                                <a href="{{ route('late-policies.show', $latePolicy) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-late-policy')
                                                <a href="{{ route('late-policies.edit', $latePolicy) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $latePolicies->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
