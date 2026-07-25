<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                    <x-heroicon name="calendar-days" class="w-5 h-5 text-indigo-600" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Daily Attendance Register</h1>
            </div>
            <a href="{{ route('attendances.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                <x-heroicon name="arrow-left" class="w-4 h-4" />
                Back to Attendance
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Date Navigation --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('attendances.daily-register', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d'), 'branch_id' => request('branch_id')]) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    <x-heroicon name="chevron-down" class="w-4 h-4 rotate-90" />
                    Previous Day
                </a>

                <div class="flex items-center gap-4">
                    <form action="{{ route('attendances.daily-register') }}" method="GET" class="flex items-center gap-3">
                        <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                            class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        <select name="branch_id" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Branches</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}" {{ request('branch_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
                </div>

                <a href="{{ route('attendances.daily-register', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d'), 'branch_id' => request('branch_id')]) }}"
                    class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Next Day
                    <x-heroicon name="chevron-down" class="w-4 h-4 -rotate-90" />
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card value="{{ $presentCount }}" label="Present" icon="check-circle" color="green" />
            <x-stat-card value="{{ $absentCount }}" label="Absent" icon="x-circle" color="red" />
            <x-stat-card value="{{ $lateCount }}" label="Late" icon="exclamation-triangle" color="amber" />
        </div>

        {{-- Register Table --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            @if ($employees->isEmpty())
                <x-empty-state title="No employees found" description="No active employees to display for this date." icon="users" />
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($employees as $emp)
                                @php
                                    $record = $emp->attendances->first();
                                    $status = $record->status ?? 'absent';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                                {{ strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1)) }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->department->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->branch->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->clock_in ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->clock_out ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->hours_worked ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-[200px] truncate">{{ $record->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
