<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Import Preview"
            icon="document-check"
            description="Review the employees before importing."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('employees.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Employees</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Import Preview</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card p-4">
                <p class="text-caption font-medium text-gray-500">Total Rows</p>
                <p class="text-title font-bold text-gray-900">{{ count($previewRows) }}</p>
            </div>
            <div class="card p-4">
                <p class="text-caption font-medium text-emerald-600">New Employees</p>
                <p class="text-title font-bold text-emerald-700">{{ collect($previewRows)->where('status', 'new')->count() }}</p>
            </div>
            <div class="card p-4">
                <p class="text-caption font-medium text-amber-600">Duplicates</p>
                <p class="text-title font-bold text-amber-700">{{ collect($previewRows)->where('status', 'duplicate')->count() }}</p>
            </div>
        </div>

        {{-- Import Options --}}
        @if (collect($previewRows)->where('status', 'duplicate')->count() > 0)
            <div class="card p-4">
                <h3 class="text-section font-semibold text-gray-900 mb-3">Import Options</h3>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 text-body text-gray-700">
                        <input type="checkbox" name="skip_duplicates" value="1" {{ $skipDuplicates ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" disabled>
                        Skip duplicates (don't import existing)
                    </label>
                    <label class="flex items-center gap-2 text-body text-gray-700">
                        <input type="checkbox" name="update_existing" value="1" {{ $updateExisting ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" disabled>
                        Update existing employees
                    </label>
                </div>
                <p class="text-caption text-gray-500 mt-2">Matching is done by email or employee number.</p>
            </div>
        @endif

        {{-- Preview Table --}}
        <div class="card overflow-hidden">
            @if (empty($previewRows))
                <div class="p-8">
                    <x-empty-state
                        icon="document-text"
                        title="No data found"
                        description="The uploaded file contains no valid employee rows."
                    />
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Row</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Employee #</th>
                                <th>Phone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($previewRows as $row)
                                <tr>
                                    <td class="text-body text-gray-500">{{ $row['row'] }}</td>
                                    <td class="text-body font-medium text-gray-900">{{ $row['first_name'] }}</td>
                                    <td class="text-body text-gray-900">{{ $row['last_name'] }}</td>
                                    <td class="text-body text-gray-500">{{ $row['email'] ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $row['employee_number'] ?? '-' }}</td>
                                    <td class="text-body text-gray-500">{{ $row['phone'] ?? '-' }}</td>
                                    <td>
                                        @if ($row['status'] === 'new')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">New</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Duplicate</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('employees.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
            <div class="flex items-center gap-3">
                @if (collect($previewRows)->where('status', 'new')->count() > 0)
                    <form action="{{ route('employees.import.commit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <button type="submit" class="btn-primary inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Import {{ collect($previewRows)->where('status', 'new')->count() }} New Employee(s)
                        </button>
                    </form>
                @else
                    <span class="text-caption text-gray-500">No new employees to import</span>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
