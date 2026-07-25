<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $latePolicy->name }}</h2>
            <div class="flex gap-2">
                @can('update-late-policy')
                    <a href="{{ route('late-policies.edit', $latePolicy) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>Edit</a>
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
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Organization</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->organization->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Grace Minutes</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->grace_minutes }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Max Late Per Month</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->max_late_per_month }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Penalty Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->penalty_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Penalty Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->penalty_amount }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $latePolicy->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$latePolicy->is_active ? 'active' : 'inactive'" /></dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-start">
                <a href="{{ route('late-policies.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Back to Late Policies</a>
            </div>
        </div>
    </div>
</x-app-layout>
