<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="users" class="w-5 h-5 text-indigo-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Employees</h1>
            </div>
            <div class="flex items-center gap-3" x-data="{ showImport: false }">
                <a href="{{ route('employees.export') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    <x-heroicon name="arrow-down-tray" class="w-4 h-4" />
                    Export
                </a>
                <a href="{{ route('employees.import.template') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    <x-heroicon name="document-text" class="w-4 h-4" />
                    Template
                </a>
                <button @click="showImport = true" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    <x-heroicon name="arrow-up-tray" class="w-4 h-4" />
                    Import
                </button>
                @can('create-employee')
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        <x-heroicon name="user-plus" class="w-4 h-4" />
                        Add Employee
                    </a>
                @endcan

                {{-- Import Modal --}}
                <div x-show="showImport" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-900/50" @click="showImport = false"></div>
                        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6" @click.stop>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Import Employees</h3>
                                <button @click="showImport = false" class="text-gray-400 hover:text-gray-600">
                                    <x-heroicon name="x-mark" class="w-5 h-5" />
                                </button>
                            </div>
                            <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="import_file" class="block text-sm font-medium text-gray-700 mb-1">Select Excel/CSV File</label>
                                    <input type="file" id="import_file" name="import_file" accept=".xlsx,.xls,.csv" required
                                        class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                                <div class="bg-amber-50 border border-amber-200 rounded-md p-3">
                                    <p class="text-xs text-amber-700">
                                        <strong>Note:</strong> Use the <a href="{{ route('employees.import.template') }}" class="underline font-medium">import template</a> for correct column formatting. First row data determines organization/branch assignment.
                                    </p>
                                </div>
                                @error('import_file')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="showImport = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Filters --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <form action="{{ route('employees.index') }}" method="GET" class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <x-search-input name="search" value="{{ request('search') }}" placeholder="Search employees..." />
                </div>
                <div class="min-w-[180px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Organization</label>
                    <select name="organization_id" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Organizations</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}" {{ $organizationId == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Department</label>
                    <select name="department_id" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <x-primary-button type="submit">Filter</x-primary-button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            @if ($employees->isEmpty())
                <x-empty-state title="No employees found" description="Get started by adding your first employee.">
                    @can('create-employee')
                        <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Add Employee</a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($employees as $emp)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                                {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}
                                            </span>
                                            <a href="{{ route('employees.show', $emp) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">
                                                {{ $emp->first_name }} {{ $emp->last_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->employee_number ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->department->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->branch->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$emp->status ?? 'active'" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @can('view-employee')
                                            <a href="{{ route('employees.show', $emp) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        @endcan
                                        @can('update-employee')
                                            <a href="{{ route('employees.edit', $emp) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
