<x-app-layout>
    <x-page-header title="Import Preview" icon="eye" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Import History', 'route' => 'attendances.import.history'],
        ['label' => 'Preview Batch #' . $batch->id],
    ]" />

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Rows</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['valid'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Valid</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-rose-600">{{ $stats['invalid'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Invalid</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $stats['duplicate'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Duplicates</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $batch->valid_rows > 0 ? 'Ready' : 'No valid rows' }}</p>
            <p class="text-xs text-gray-500 mt-1">Status</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 mb-6">
        @if($batch->valid_rows > 0)
            <form action="{{ route('attendances.import.commit', $batch) }}" method="POST" onsubmit="return confirm('Import {{ $batch->valid_rows }} valid attendance records?')">
                @csrf
                <button type="submit" class="btn-primary">
                    <x-heroicon name="check" class="w-4 h-4" />
                    Confirm Import ({{ $batch->valid_rows }} records)
                </button>
            </form>
        @endif
        @if($batch->invalid_rows > 0)
            <a href="{{ route('attendances.import.download-invalid', $batch) }}" class="btn-secondary">
                <x-heroicon name="document-arrow-down" class="w-4 h-4" />
                Download Invalid Rows
            </a>
        @endif
        <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>Upload New File</a>
    </div>

    {{-- Preview Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell">Row</th>
                        <th class="table-header-cell">Employee Code</th>
                        <th class="table-header-cell">Employee Name</th>
                        <th class="table-header-cell">Date</th>
                        <th class="table-header-cell">Clock In</th>
                        <th class="table-header-cell">Clock Out</th>
                        <th class="table-header-cell">Status</th>
                        <th class="table-header-cell">Validation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rows as $row)
                        <tr class="table-row {{ $row->status === 'invalid' ? 'bg-rose-50' : ($row->status === 'duplicate' ? 'bg-amber-50' : '') }}">
                            <td class="table-cell text-gray-400">{{ $row->row_number }}</td>
                            <td class="table-cell font-medium">{{ $row->employee_code }}</td>
                            <td class="table-cell">{{ $row->employee_name ?: '-' }}</td>
                            <td class="table-cell">{{ $row->date }}</td>
                            <td class="table-cell">{{ $row->clock_in ?: '-' }}</td>
                            <td class="table-cell">{{ $row->clock_out ?: '-' }}</td>
                            <td class="table-cell">{{ ucfirst($row->status) }}</td>
                            <td class="table-cell">
                                @if($row->errors)
                                    <span class="text-xs text-rose-600">{{ $row->getErrorSummary() }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-700">Valid</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No rows to preview.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
