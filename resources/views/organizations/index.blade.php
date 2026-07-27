<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Organizations"
            icon="building-storefront"
            description="Manage your organization entities and company profiles."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">People</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Organizations</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-organization')
                    <a href="{{ route('organizations.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Organization
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
            <form action="{{ route('organizations.index') }}" method="GET">
                <div class="filter-bar-inner">
                    <div class="filter-group flex-1 min-w-[200px]">
                        <label class="filter-label">Search</label>
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search organizations..." />
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">&nbsp;</label>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                            Search
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            @if ($organizations->isEmpty())
                <x-empty-state
                    icon="building-storefront"
                    title="No organizations found"
                    description="Get started by creating your first organization."
                >
                    @can('create-organization')
                        <a href="{{ route('organizations.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Create Organization
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Legal Name</th>
                                <th>Tax ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organizations as $org)
                                <tr>
                                    <td>
                                        <a href="{{ route('organizations.show', $org) }}" class="text-body font-medium text-gray-900 hover:text-primary-600 transition-colors" wire:navigate>{{ $org->name }}</a>
                                    </td>
                                    <td class="text-body text-gray-500">{{ $org->legal_name ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $org->tax_id ?? '-' }}</td>
                                    <td><x-status-badge :status="$org->status ?? 'active'" /></td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @can('view-organization')
                                                <a href="{{ route('organizations.show', $org) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                            @endcan
                                            @can('update-organization')
                                                <a href="{{ route('organizations.edit', $org) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $organizations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
