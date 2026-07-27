<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="{{ $user->name }}"
            icon="user"
            description="{{ ucfirst($user->role) }} at {{ $user->organization->name ?? 'Organization' }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('user-management.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">User Management</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">{{ $user->name }}</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    @can('update-user-management')
                        <a href="{{ route('user-management.edit', $user) }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit
                        </a>
                    @endcan
                    @can('update-user-management')
                        <form action="{{ route('user-management.toggle-status', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="{{ $user->status === 'active' ? 'btn-danger' : 'btn-success' }} inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    @endcan
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-card p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-body font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Profile Header --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 text-xl font-bold text-primary-700 border-2 border-primary-100">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-title font-semibold text-gray-900">{{ $user->name }}</h2>
                        <x-status-badge :status="$user->status" />
                    </div>
                    <p class="text-body text-gray-500 mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- User Information --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                        <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h3 class="text-section font-semibold text-gray-900">User Information</h3>
                </div>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Name</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Email</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Role</dt>
                        <dd>
                            @if($user->role === 'super-admin')
                                <span class="badge-info">Super Admin</span>
                            @elseif($user->role === 'branch-admin')
                                <span class="badge-success">Branch Admin</span>
                            @else
                                <span class="badge-neutral">{{ ucfirst($user->role) }}</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Status</dt>
                        <dd><x-status-badge :status="$user->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Organization</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $user->organization->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Branch</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $user->branch->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Created</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Back Link --}}
        <div class="pt-4">
            <a href="{{ route('user-management.index') }}" class="text-body text-gray-500 hover:text-gray-900 inline-flex items-center gap-2 transition-colors" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Users
            </a>
        </div>
    </div>
</x-app-layout>
