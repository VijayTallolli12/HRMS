<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporting Hierarchies</h2>
            @can('create-reporting-hierarchy')
                <a href="{{ route('reporting-hierarchies.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                    Create Reporting Hierarchy
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
                <form action="{{ route('reporting-hierarchies.index') }}" method="GET" class="flex gap-2 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search reporting hierarchies..." />
                    </div>
                    <div class="min-w-[180px]">
                        <select name="employee_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Employees</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-[150px]">
                        <select name="reporting_type" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Types</option>
                            <option value="Direct" {{ request('reporting_type') == 'Direct' ? 'selected' : '' }}>Direct</option>
                            <option value="Functional" {{ request('reporting_type') == 'Functional' ? 'selected' : '' }}>Functional</option>
                            <option value="Administrative" {{ request('reporting_type') == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                        </select>
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($reportingHierarchies->isEmpty())
                    <x-empty-state title="No reporting hierarchies found" description="Get started by creating your first reporting hierarchy.">
                        @can('create-reporting-hierarchy')
                            <a href="{{ route('reporting-hierarchies.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700" wire:navigate>
                                Create Reporting Hierarchy
                            </a>
                        @endcan
                    </x-empty-state>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manager</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective From</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective To</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reportingHierarchies as $reportingHierarchy)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('reporting-hierarchies.show', $reportingHierarchy) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>{{ $reportingHierarchy->employee->first_name }} {{ $reportingHierarchy->employee->last_name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reportingHierarchy->manager->first_name }} {{ $reportingHierarchy->manager->last_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reportingHierarchy->reporting_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reportingHierarchy->effective_from }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reportingHierarchy->effective_to ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$reportingHierarchy->is_active ? 'active' : 'inactive'" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            @can('view-reporting-hierarchy')
                                                <a href="{{ route('reporting-hierarchies.show', $reportingHierarchy) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>View</a>
                                            @endcan
                                            @can('update-reporting-hierarchy')
                                                <a href="{{ route('reporting-hierarchies.edit', $reportingHierarchy) }}" class="text-indigo-600 hover:text-indigo-900" wire:navigate>Edit</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $reportingHierarchies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
