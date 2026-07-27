<x-app-layout>
    <x-page-header title="Attendance Records" description="Track, manage, and review employee attendance data across your organization." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Attendance Records</span>
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('attendances.daily-register') }}" class="btn-secondary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                Daily Register
            </a>
            <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Import
            </a>
            @can('create-attendance')
                <a href="{{ route('attendances.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add Attendance
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        @php
            $presentCount = $attendances->filter(fn($a) => $a->status === 'present')->count();
            $absentCount = $attendances->filter(fn($a) => $a->status === 'absent')->count();
            $lateCount = $attendances->filter(fn($a) => $a->status === 'late')->count();
            $totalRecords = $attendances->total();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card value="{{ $totalRecords }}" label="Total Records" icon="document-text" color="indigo" />
            <x-stat-card value="{{ $presentCount }}" label="Present" icon="check-circle" color="green" />
            <x-stat-card value="{{ $absentCount }}" label="Absent" icon="x-circle" color="red" />
            <x-stat-card value="{{ $lateCount }}" label="Late" icon="exclamation-triangle" color="amber" />
        </div>

        <div class="filter-bar">
            <form action="{{ route('attendances.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search by employee name..." />
                </div>
                <div class="filter-group min-w-[140px]">
                    <label class="filter-label">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
                </div>
                <div class="filter-group min-w-[140px]">
                    <label class="filter-label">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
                </div>
                <div class="filter-group min-w-[160px]">
                    <label class="filter-label">Status</label>
                    <select name="status" class="select-field">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="half-day" {{ request('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                        <option value="remote" {{ request('status') == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($attendances->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No attendance records found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by recording your first attendance.</p>
                    @can('create-attendance')
                        <a href="{{ route('attendances.create') }}" class="btn-primary" wire:navigate>Add Attendance</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Status</th>
                                <th>Hours</th>
                                <th>Overtime</th>
                                <th>Late</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>
                                        <a href="{{ route('attendances.show', $attendance) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>
                                            {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $attendance->date?->format('M d, Y') ?? $attendance->date }}</td>
                                    <td>{{ $attendance->clock_in ?? '-' }}</td>
                                    <td>{{ $attendance->clock_out ?? '-' }}</td>
                                    <td><x-status-badge :status="$attendance->status" /></td>
                                    <td>{{ $attendance->hours_worked ?? '-' }}</td>
                                    <td>{{ $attendance->overtime_hours ?? '-' }}</td>
                                    <td>{{ ($attendance->late_minutes ?? 0) > 0 ? $attendance->late_minutes . 'm' : '-' }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-attendance')
                                                <a href="{{ route('attendances.show', $attendance) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>
                                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    View
                                                </a>
                                            @endcan
                                            @can('update-attendance')
                                                <a href="{{ route('attendances.edit', $attendance) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>
                                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                                    Edit
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
