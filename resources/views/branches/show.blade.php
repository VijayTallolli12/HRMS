<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="{{ $branch->name }}"
            icon="map-pin"
            description="{{ $branch->organization->name ?? 'Branch' }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('branches.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Branches</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">{{ $branch->name }}</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    @can('update-branch')
                        <a href="{{ route('branches.edit', $branch) }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit
                        </a>
                    @endcan
                    @can('delete-branch')
                        <x-delete-confirm route="{{ route('branches.destroy', $branch) }}">
                            Delete
                        </x-delete-confirm>
                    @endcan
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        {{-- Details Card --}}
        <div class="card">
            <div class="card-body">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Organization</dt>
                        <dd class="text-body text-gray-900">{{ $branch->organization->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Phone</dt>
                        <dd class="text-body text-gray-900">{{ $branch->phone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Status</dt>
                        <dd class="mt-0.5"><x-status-badge :status="$branch->status ?? 'active'" /></dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Employees</dt>
                        <dd class="text-body text-gray-900">{{ $branch->employees->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Back Link --}}
    <div class="mt-8 pt-6 border-t border-gray-100">
        <a href="{{ route('branches.index') }}" class="text-body text-gray-500 hover:text-gray-900 inline-flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Branches
        </a>
    </div>
</x-app-layout>
