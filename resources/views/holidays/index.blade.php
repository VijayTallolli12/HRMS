<x-app-layout>
    <x-page-header title="Holidays" description="Manage company holidays, public holidays, and optional time off." icon="cake">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Holidays</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-holiday')
                <a href="{{ route('holidays.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Holiday
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('holidays.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search holidays..." />
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
                <div class="filter-group min-w-[150px]">
                    <label class="filter-label">Type</label>
                    <select name="type" class="select-field">
                        <option value="">All Types</option>
                        <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="optional" {{ request('type') == 'optional' ? 'selected' : '' }}>Optional</option>
                        <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>Company</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($holidays->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    <p class="text-title text-gray-900 mb-1">No holidays found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first holiday.</p>
                    @can('create-holiday')
                        <a href="{{ route('holidays.create') }}" class="btn-primary" wire:navigate>Create Holiday</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Organization</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holidays as $holiday)
                                <tr>
                                    <td>
                                        <a href="{{ route('holidays.show', $holiday) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $holiday->name }}</a>
                                    </td>
                                    <td>{{ $holiday->date }}</td>
                                    <td><span class="badge-{{ $holiday->type === 'public' ? 'primary' : ($holiday->type === 'optional' ? 'info' : 'success') }}">{{ ucfirst($holiday->type) }}</span></td>
                                    <td>{{ $holiday->organization->name }}</td>
                                    <td><x-status-badge :status="$holiday->is_active ? 'active' : 'inactive'" /></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-holiday')
                                                <a href="{{ route('holidays.show', $holiday) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-holiday')
                                                <a href="{{ route('holidays.edit', $holiday) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $holidays->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
