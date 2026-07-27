<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="User Management"
            icon="users"
            description="Manage system users, roles, and access permissions."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">User Management</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-user-management')
                    <a href="{{ route('user-management.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                        Add User
                    </a>
                @endcan
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        {{-- Filters --}}
        <div class="filter-bar">
            <form action="{{ route('user-management.index') }}" method="GET">
                <div class="filter-bar-inner">
                    <div class="filter-group flex-1 min-w-[200px]">
                        <label class="filter-label">Search</label>
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search users..." />
                    </div>
                    <div class="filter-group min-w-[160px]">
                        <label class="filter-label">Role</label>
                        <select name="role" class="select-field">
                            <option value="">All Roles</option>
                            <option value="super-admin" {{ request('role') === 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="branch-admin" {{ request('role') === 'branch-admin' ? 'selected' : '' }}>Branch Admin</option>
                        </select>
                    </div>
                    <div class="filter-group min-w-[140px]">
                        <label class="filter-label">Status</label>
                        <select name="status" class="select-field">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
            @if($users->isEmpty())
                <x-empty-state
                    icon="users"
                    title="No users found"
                    description="Get started by adding a new user."
                >
                    @can('create-user-management')
                        <a href="{{ route('user-management.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Add User
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Branch</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary-50 text-sm font-bold text-primary-700 border border-primary-100">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                            <a href="{{ route('user-management.show', $user) }}" class="text-body font-medium text-gray-900 hover:text-primary-600 transition-colors" wire:navigate>{{ $user->name }}</a>
                                        </div>
                                    </td>
                                    <td class="text-body text-gray-500">{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'super-admin')
                                            <span class="badge-info">Super Admin</span>
                                        @elseif($user->role === 'branch-admin')
                                            <span class="badge-success">Branch Admin</span>
                                        @else
                                            <span class="badge-neutral">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td><x-status-badge :status="$user->status" /></td>
                                    <td class="text-body text-gray-500">{{ $user->branch->name ?? '-' }}</td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('user-management.edit', $user) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                            @can('update-user-management')
                                                <form action="{{ route('user-management.toggle-status', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-caption font-medium transition-colors {{ $user->status === 'active' ? 'text-red-600 hover:text-red-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                                        {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
