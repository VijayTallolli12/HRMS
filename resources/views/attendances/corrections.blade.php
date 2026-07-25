<x-app-layout>
    <x-page-header title="Attendance Corrections" icon="pencil-square" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Corrections'],
    ]" />

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="label">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Name or employee code..." />
            </div>
            <div>
                <label class="label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
            </div>
            <div>
                <label class="label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
            </div>
            <div>
                <label class="label">Source</label>
                <select name="source" class="select-field">
                    <option value="">All Sources</option>
                    <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="import" {{ request('source') === 'import' ? 'selected' : '' }}>Import</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell">Employee</th>
                        <th class="table-header-cell">Date</th>
                        <th class="table-header-cell">Clock In</th>
                        <th class="table-header-cell">Clock Out</th>
                        <th class="table-header-cell">Hours</th>
                        <th class="table-header-cell">Status</th>
                        <th class="table-header-cell">Source</th>
                        <th class="table-header-cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $attendance)
                        <tr class="table-row">
                            <td class="table-cell font-medium">{{ $attendance->employee->first_name ?? '' }} {{ $attendance->employee->last_name ?? '' }}</td>
                            <td class="table-cell">{{ $attendance->date->format('M d, Y') }}</td>
                            <td class="table-cell">{{ $attendance->clock_in ?: '-' }}</td>
                            <td class="table-cell">{{ $attendance->clock_out ?: '-' }}</td>
                            <td class="table-cell">{{ number_format($attendance->hours_worked, 2) }}h</td>
                            <td class="table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $attendance->status === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $attendance->status === 'late' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $attendance->status === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                <span class="text-xs text-gray-500">{{ ucfirst($attendance->source ?? 'manual') }}</span>
                            </td>
                            <td class="table-cell">
                                <a href="{{ route('attendances.corrections.edit', $attendance) }}" class="text-indigo-600 hover:text-indigo-500 text-xs font-medium" wire:navigate>Correct</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $attendances->links() }}</div>
        @endif
    </div>
</x-app-layout>
