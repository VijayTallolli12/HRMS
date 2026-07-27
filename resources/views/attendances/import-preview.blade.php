<x-app-layout>
    <x-page-header title="Import Preview" icon="eye">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.import.history') }}" wire:navigate>Import History</a>
            <span class="breadcrumb-separator">/</span>
            <span>Batch #{{ $batch->id }}</span>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-caption text-gray-500 mt-1">Total Rows</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-emerald-600">{{ $stats['valid'] }}</p>
            <p class="text-caption text-gray-500 mt-1">Valid</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-rose-600">{{ $stats['invalid'] }}</p>
            <p class="text-caption text-gray-500 mt-1">Invalid</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-amber-600">{{ $stats['duplicate'] }}</p>
            <p class="text-caption text-gray-500 mt-1">Duplicates</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-primary-600">{{ $batch->valid_rows > 0 ? 'Ready' : 'No valid rows' }}</p>
            <p class="text-caption text-gray-500 mt-1">Status</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-6">
        @if($batch->valid_rows > 0)
            <form action="{{ route('attendances.import.commit', $batch) }}" method="POST" onsubmit="return confirm('Import {{ $batch->valid_rows }} valid attendance records?')">
                @csrf
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    Confirm Import ({{ $batch->valid_rows }} records)
                </button>
            </form>
        @endif
        @if($batch->invalid_rows > 0)
            <a href="{{ route('attendances.import.download-invalid', $batch) }}" class="btn-secondary">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download Invalid Rows
            </a>
        @endif
        <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>Upload New File</a>
    </div>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Row</th>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Status</th>
                        <th>Validation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr class="{{ $row->status === 'invalid' ? 'bg-red-50/50' : ($row->status === 'duplicate' ? 'bg-amber-50/50' : '') }}">
                            <td class="text-caption text-gray-400">{{ $row->row_number }}</td>
                            <td class="font-medium">{{ $row->employee_code }}</td>
                            <td>{{ $row->employee_name ?: '-' }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->clock_in ?: '-' }}</td>
                            <td>{{ $row->clock_out ?: '-' }}</td>
                            <td>{{ ucfirst($row->status) }}</td>
                            <td>
                                @if($row->errors)
                                    <span class="text-caption text-red-600">{{ $row->getErrorSummary() }}</span>
                                @else
                                    <span class="badge-success">Valid</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No rows to preview</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
