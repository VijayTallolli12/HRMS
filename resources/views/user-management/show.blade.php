<x-app-layout>
    <x-page-header title="User Details" icon="heroicon-o-user">
        <div class="flex items-center gap-3">
            <x-secondary-button tag="a" href="{{ route('user-management.index') }}" wire:navigate>
                <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
                Back
            </x-secondary-button>
            @can('edit-users')
                <x-primary-button tag="a" href="{{ route('user-management.edit', $user) }}" wire:navigate>
                    <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
                    Edit
                </x-primary-button>
            @endcan
        </div>
    </x-page-header>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex items-center gap-2">
                <x-heroicon name="heroicon-o-check-circle" class="h-5 w-5 text-green-600" />
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="label">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="label">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="label">Role</dt>
                            <dd class="mt-1">
                                @if($user->role === 'super-admin')
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">Super Admin</span>
                                @elseif($user->role === 'branch-admin')
                                    <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-1 text-xs font-medium text-sky-700">Branch Admin</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700">{{ ucfirst($user->role) }}</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="label">Status</dt>
                            <dd class="mt-1"><x-status-badge :status="$user->status" /></dd>
                        </div>
                        <div>
                            <dt class="label">Organization</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->organization->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="label">Branch</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->branch->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="label">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('edit-users')
                        <a href="{{ route('user-management.edit', $user) }}" class="btn-primary w-full justify-center" wire:navigate>
                            <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
                            Edit User
                        </a>
                    @endcan
                    @can('toggle-user-status')
                        <form action="{{ route('user-management.toggle-status', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full {{ $user->status === 'active' ? 'btn-danger' : 'btn-primary' }} justify-center">
                                {{ $user->status === 'active' ? 'Deactivate User' : 'Activate User' }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
