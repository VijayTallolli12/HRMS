<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $costCenter->name }}</h2>
            <div class="flex gap-2">
                @can('update-cost-center')
                    <a href="{{ route('cost-centers.edit', $costCenter) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>Edit</a>
                @endcan
                @can('delete-cost-center')
                    <x-delete-confirm route="{{ route('cost-centers.destroy', $costCenter) }}">Delete</x-delete-confirm>
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
                        <dt class="text-sm font-medium text-gray-500">Code</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Organization</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->organization->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Department</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->department->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Budget</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $costCenter->budget ? number_format($costCenter->budget, 2) : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$costCenter->is_active ? 'active' : 'inactive'" /></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
