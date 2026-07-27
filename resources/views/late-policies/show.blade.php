<x-app-layout>
    <x-page-header title="Late Policy Details" description="View complete information for this late policy." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('late-policies.index') }}" wire:navigate>Late Policies</a>
            <span class="breadcrumb-separator">/</span>
            <span>{{ $latePolicy->name }}</span>
        </x-slot>
        <x-slot name="actions">
            @can('update-late-policy')
                <a href="{{ route('late-policies.edit', $latePolicy) }}" class="btn-secondary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Edit
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-card bg-amber-50">
                    <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                </div>
                <h3 class="text-section font-semibold text-gray-900">Policy Information</h3>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Name</dt>
                    <dd class="text-body font-semibold text-gray-900">{{ $latePolicy->name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Organization</dt>
                    <dd class="text-body text-gray-900">{{ $latePolicy->organization->name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Status</dt>
                    <dd><x-status-badge :status="$latePolicy->is_active ? 'active' : 'inactive'" /></dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Grace Minutes</dt>
                    <dd class="text-body text-gray-900">{{ $latePolicy->grace_minutes }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Max Late/Month</dt>
                    <dd class="text-body text-gray-900">{{ $latePolicy->max_late_per_month }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Penalty Type</dt>
                    <dd class="text-body text-gray-900">{{ ucfirst($latePolicy->penalty_type) }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Penalty Amount</dt>
                    <dd class="text-body text-gray-900">{{ $latePolicy->penalty_amount ?: '-' }}</dd>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <dt class="text-caption text-gray-500 mb-1">Description</dt>
                    <dd class="text-body text-gray-900">{{ $latePolicy->description ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>
