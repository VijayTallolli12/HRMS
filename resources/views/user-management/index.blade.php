<x-app-layout>
    <x-page-header title="User Management" icon="heroicon-o-users">
        @can('create-users')
            <x-primary-button tag="a" href="{{ route('user-management.create') }}" wire:navigate>
                <x-heroicon name="heroicon-o-plus" class="h-4 w-4" />
                Add User
            </x-primary-button>
        @endcan
    </x-page-header>

    <div class="card">
        <div class="p-6 border-b border-gray-100">
            <form method="GET" action="{{ route('user-management.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-search-input type="text" name="search" placeholder="Search users..." value="{{ old('search', request('search')) }}" />
                </div>
                <div class="sm:w-48">
                    <select name="role" class="select-field" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="super-admin" {{ request('role') === 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="branch-admin" {{ request('role') === 'branch-admin' ? 'selected' : '' }}>Branch Admin</option>
                    </select>
                </div>
                <div class="sm:w-48">
                    <select name="status" class="select-field" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </form>
        </div>

        @if($users->isEmpty())
            <x-empty-state icon="heroicon-o-users" title="No users found" description="Get started by adding a new user." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Name</th>
                            <th class="table-header text-left">Email</th>
                            <th class="table-header text-left">Role</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-left">Branch</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="table-row">
                                <td class="table-cell">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ route('user-management.show', $user) }}" class="font-medium text-gray-900 hover:text-indigo-600" wire:navigate>{{ $user->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-cell text-gray-500">{{ $user->email }}</td>
                                <td class="table-cell">
                                    @if($user->role === 'super-admin')
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">Super Admin</span>
                                    @elseif($user->role === 'branch-admin')
                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-1 text-xs font-medium text-sky-700">Branch Admin</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td class="table-cell">
                                    <x-status-badge :status="$user->status" />
                                </td>
                                <td class="table-cell text-gray-500">{{ $user->branch->name ?? '-' }}</td>
                                <td class="table-cell text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('user-management.edit', $user) }}" class="btn-secondary btn-sm" wire:navigate>
                                            <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
                                            Edit
                                        </a>
                                        @can('toggle-user-status')
                                            <form action="{{ route('user-management.toggle-status', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-primary' }}">
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
</x-app-layout>
