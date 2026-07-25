<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Holidays</h2>
            @can('create-holiday')
                <a href="{{ route('holidays.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                    Create Holiday
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md text-green-700" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <form action="{{ route('holidays.index') }}" method="GET" class="flex gap-2 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search holidays..." />
                    </div>
                    <div class="min-w-[180px]">
                        <select name="organization_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Organizations</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <select name="type" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Types</option>
                            <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="optional" {{ request('type') == 'optional' ? 'selected' : '' }}>Optional</option>
                            <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>Company</option>
                        </select>
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($holidays->isEmpty())
                    <x-empty-state title="No holidays found" description="Get started by creating your first holiday.">
                        @can('create-holiday')
                            <a href="{{ route('holidays.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>
                                Create Holiday
                            </a>
                        @endcan
                    </x-empty-state>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($holidays as $holiday)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('holidays.show', $holiday) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>{{ $holiday->name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holiday->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($holiday->type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holiday->organization->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$holiday->is_active ? 'active' : 'inactive'" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @can('view-holiday')
                                                <a href="{{ route('holidays.show', $holiday) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>View</a>
                                            @endcan
                                            @can('update-holiday')
                                                <a href="{{ route('holidays.edit', $holiday) }}" class="text-indigo-600 hover:text-indigo-900" wire:navigate>Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $holidays->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
