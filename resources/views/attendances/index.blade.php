<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="clock" class="w-5 h-5 text-indigo-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Attendance</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('attendances.daily-register') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    <x-heroicon name="calendar-days" class="w-4 h-4" />
                    Daily Register
                </a>
                <button x-data="{ showImport: false }" @click="showImport = true" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    <x-heroicon name="arrow-up-tray" class="w-4 h-4" />
                    Import
                </button>
                @can('create-attendance')
                    <a href="{{ route('attendances.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        <x-heroicon name="plus" class="w-4 h-4" />
                        Add Attendance
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Stats Summary --}}
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

        {{-- Filters --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <form action="{{ route('attendances.index') }}" method="GET" class="flex gap-3 flex-wrap items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <x-search-input name="search" value="{{ request('search') }}" placeholder="Search by employee name..." />
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="half-day" {{ request('status') == 'half-day' ? 'selected' : '' }}>Half Day</option>
                        <option value="remote" {{ request('status') == 'remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>
                <x-primary-button type="submit">Filter</x-primary-button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            @if ($attendances->isEmpty())
                <x-empty-state title="No attendance records found" description="Get started by recording your first attendance.">
                    @can('create-attendance')
                        <a href="{{ route('attendances.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Add Attendance</a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($attendances as $attendance)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('attendances.show', $attendance) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->date?->format('M d, Y') ?? $attendance->date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->clock_in ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->clock_out ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$attendance->status" /></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->hours_worked ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->overtime_hours ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ($attendance->late_minutes ?? 0) > 0 ? $attendance->late_minutes . 'm' : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @can('view-attendance')
                                            <a href="{{ route('attendances.show', $attendance) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        @endcan
                                        @can('update-attendance')
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
