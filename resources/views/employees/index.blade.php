<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Employees"
            icon="users"
            description="Manage all employees in your organization."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">People</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Employees</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3" x-data="{ showImport: false }">
                    <a href="{{ route('employees.export') }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Export
                    </a>
                    <a href="{{ route('employees.import.template') }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        Template
                    </a>
                    <button @click="showImport = true" class="btn-secondary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Bulk Import
                    </button>
                    @can('create-employee')
                        <a href="{{ route('employees.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Add Employee
                        </a>
                    @endcan

                    {{-- Import Modal --}}
                    <div x-show="showImport" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4">
                            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showImport = false"></div>
                            <div class="relative card w-full max-w-lg p-0 shadow-dropdown" @click.stop>
                                <div class="card-header flex items-center justify-between">
                                    <h3 class="text-section font-semibold text-gray-900">Import Employees</h3>
                                    <button @click="showImport = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="label" for="import_file">Select Excel/CSV File</label>
                                            <input type="file" id="import_file" name="import_file" accept=".xlsx,.xls,.csv" required
                                                class="input-field mt-1" />
                                        </div>
                                        <div class="bg-amber-50 border border-amber-200 rounded-input p-3">
                                            <p class="text-caption text-amber-700">
                                                <strong>Note:</strong> Use the <a href="{{ route('employees.import.template') }}" class="underline font-medium text-amber-800 hover:text-amber-900">import template</a> for correct column formatting. First row data determines organization/branch assignment.
                                            </p>
                                        </div>
                                        @error('import_file')
                                            <p class="text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="showImport = false" class="btn-secondary">Cancel</button>
                                            <button type="submit" class="btn-primary inline-flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                                Import
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-card text-emerald-700 text-body p-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-card text-red-700 text-body p-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="filter-bar">
            <form action="{{ route('employees.index') }}" method="GET">
                <div class="filter-bar-inner">
                    <div class="filter-group flex-1 min-w-[200px]">
                        <label class="filter-label">Search</label>
                        <x-search-input name="search" value="{{ request('search') }}" placeholder="Search employees..." />
                    </div>
                    <div class="filter-group min-w-[180px]">
                        <label class="filter-label">Branch</label>
                        <select name="branch_id" class="select-field">
                            <option value="">All Branches</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group min-w-[160px]">
                        <label class="filter-label">Department</label>
                        <select name="department_id" class="select-field">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group min-w-[140px]">
                        <label class="filter-label">Designation</label>
                        <select name="designation_id" class="select-field">
                            <option value="">All Designations</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}" {{ request('designation_id') == $designation->id ? 'selected' : '' }}>{{ $designation->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group min-w-[140px]">
                        <label class="filter-label">Status</label>
                        <select name="status" class="select-field">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">&nbsp;</label>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" /></svg>
                            Filter
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            @if ($employees->isEmpty())
                <x-empty-state
                    icon="users"
                    title="No employees found"
                    description="Get started by adding your first employee to your organization."
                >
                    @can('create-employee')
                        <a href="{{ route('employees.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            Add Employee
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Employee Code</th>
                                <th>Employee Name</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Employment Type</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Joining Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $emp)
                                <tr>
                                    <td>
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-primary-50 text-sm font-bold text-primary-700 border border-primary-100">
                                            {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}
                                        </span>
                                    </td>
                                    <td class="text-body text-gray-500">{{ $emp->employee_number ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('employees.show', $emp) }}" class="text-body font-medium text-gray-900 hover:text-primary-600 transition-colors" wire:navigate>
                                            {{ $emp->first_name }} {{ $emp->last_name }}
                                        </a>
                                    </td>
                                    <td class="text-body text-gray-500">{{ $emp->branch->name ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $emp->department->name ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $emp->designation->title ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $emp->employmentType->name ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $emp->phone ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $emp->email ?? '-' }}</td>
                                    <td><x-status-badge :status="$emp->status ?? 'active'" /></td>
                                    <td class="text-body text-gray-500">{{ $emp->hired_at?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            @can('view', $emp)
                                                <a href="{{ route('employees.show', $emp) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                            @endcan
                                            @can('update', $emp)
                                                <a href="{{ route('employees.edit', $emp) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                                @if (($emp->status ?? 'active') === 'active')
                                                    <form action="{{ route('employees.deactivate', $emp) }}" method="POST" onsubmit="return confirm('Deactivate this employee?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-caption font-medium text-amber-600 hover:text-amber-700 transition-colors">Deactivate</button>
                                                    </form>
                                                @endif
                                            @endcan
                                            @can('delete', $emp)
                                                <x-delete-confirm route="{{ route('employees.destroy', $emp) }}" class="!px-2 !py-1 !text-xs">
                                                    Delete
                                                </x-delete-confirm>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
