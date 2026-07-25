<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $organization->name }}</h2>
            <div class="flex gap-2">
                @can('update-organization')
                    <a href="{{ route('organizations.edit', $organization) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>
                        Edit
                    </a>
                @endcan
                @can('delete-organization')
                    <x-delete-confirm route="{{ route('organizations.destroy', $organization) }}">
                        Delete
                    </x-delete-confirm>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Legal Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $organization->legal_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tax ID</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $organization->tax_id ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$organization->status ?? 'active'" /></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $organization->created_at->format('M d, Y') }}</dd>
                    </div>
                    @if ($organization->address)
                        <div class="col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $organization->address['street'] ?? '' }}
                                {{ $organization->address['city'] ?? '' }}, {{ $organization->address['state'] ?? '' }}
                                {{ $organization->address['country'] ?? '' }} {{ $organization->address['zip'] ?? '' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ $organization->branches_count ?? $organization->branches->count() }}</div>
                    <div class="text-sm text-gray-500">Branches</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ $organization->departments_count ?? $organization->departments->count() }}</div>
                    <div class="text-sm text-gray-500">Departments</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ $organization->employees_count ?? $organization->employees->count() }}</div>
                    <div class="text-sm text-gray-500">Employees</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
