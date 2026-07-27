<x-app-layout>
    <x-page-header title="Attendance Corrections" icon="pencil-square">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Corrections</span>
        </x-slot>
    </x-page-header>

    <div class="filter-bar">
        <form method="GET" class="filter-bar-inner">
            <div class="filter-group flex-1 min-w-[200px]">
                <label class="filter-label">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Name or employee code..." />
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">Source</label>
                <select name="source" class="select-field">
                    <option value="">All Sources</option>
                    <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="import" {{ request('source') === 'import' ? 'selected' : '' }}>Import</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Source</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td class="font-medium">{{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}</td>
                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                            <td>{{ $attendance->clock_in ?: '-' }}</td>
                            <td>{{ $attendance->clock_out ?: '-' }}</td>
                            <td>{{ number_format($attendance->hours_worked, 2) }}h</td>
                            <td>
                                <span class="badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'late' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="text-caption text-gray-500 capitalize">{{ $attendance->source ?? 'manual' }}</td>
                            <td>
                                <a href="{{ route('attendances.corrections.edit', $attendance) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Correct</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No records found</p>
                                    <p class="text-caption text-gray-500">No attendance records found matching your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $attendances->links() }}</div>
        @endif
    </div>
</x-app-layout>
