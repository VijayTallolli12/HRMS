<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Branches"
            icon="map-pin"
            description="Manage office locations and branch offices."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">People</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Branches</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-branch')
                    <a href="{{ route('branches.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Branch
                    </a>
                @endcan
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-card text-emerald-700 text-body p-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="filter-bar">
            <form action="{{ route('branches.index') }}" method="GET">
                <div class="filter-bar-inner">
                    <div class="filter-group flex-1 min-w-[200px]">
                        <label class="filter-label">Search</label>
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search branches..." />
                    </div>
                    <div class="filter-group min-w-[180px]">
                        <label class="filter-label">Organization</label>
                        <select name="organization_id" class="select-field">
                            <option value="">All Organizations</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}" {{ $organizationId == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">&nbsp;</label>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                            Filter
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            @if ($branches->isEmpty())
                <x-empty-state
                    icon="map-pin"
                    title="No branches found"
                    description="Get started by creating your first branch office."
                >
                    @can('create-branch')
                        <a href="{{ route('branches.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Create Branch
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Organization</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($branches as $branch)
                                <tr>
                                    <td>
                                        <a href="{{ route('branches.show', $branch) }}" class="text-body font-medium text-gray-900 hover:text-primary-600 transition-colors" wire:navigate>{{ $branch->name }}</a>
                                    </td>
                                    <td class="text-body text-gray-500">{{ $branch->organization->name ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $branch->phone ?? '-' }}</td>
                                    <td><x-status-badge :status="$branch->status ?? 'active'" /></td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @can('view-branch')
                                                <a href="{{ route('branches.show', $branch) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                            @endcan
                                            @can('update-branch')
                                                <a href="{{ route('branches.edit', $branch) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $branches->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
