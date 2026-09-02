<x-app-layout>
    <x-page-header title="Daily Attendance Register" icon="clipboard-document-list">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Daily Register</span>
        </x-slot>
    </x-page-header>

    <div class="filter-bar">
        <form method="GET" class="filter-bar-inner">
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="input-field" />
            </div>
            <div class="filter-group min-w-[180px]">
                <label class="filter-label">Branch</label>
                <select name="branch_id" class="select-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($branchId ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">View</button>
            @php
                $prevDate = \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d');
                $nextDate = \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d');
            @endphp
            <div class="flex items-center gap-2 ml-auto">
                <a href="{{ route('attendances.daily-register', ['date' => $prevDate, 'branch_id' => $branchId]) }}" class="btn-secondary px-3 py-2" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </a>
                <span class="text-body font-semibold text-gray-900 px-2">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
                <a href="{{ route('attendances.daily-register', ['date' => $nextDate, 'branch_id' => $branchId]) }}" class="btn-secondary px-3 py-2" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-gray-900">{{ $employees->count() }}</p>
            <p class="text-caption text-gray-500 mt-1">Total Employees</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-emerald-600">{{ $presentCount }}</p>
            <p class="text-caption text-gray-500 mt-1">Present</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-rose-600">{{ $absentCount }}</p>
            <p class="text-caption text-gray-500 mt-1">Absent</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-display font-bold text-amber-600">{{ $lateCount }}</p>
            <p class="text-caption text-gray-500 mt-1">Late</p>
        </div>
    </div>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Clock In</th>
                        <th>Clock Out</th>
                        <th class="text-center">Hours</th>
                        <th>Status</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                        @php $att = $emp->attendances->first(); @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                                        <span class="text-xs font-bold text-primary-600">{{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-body">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                        <p class="text-caption text-gray-400">{{ $emp->employee_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $emp->department->name ?? '-' }}</td>
                            <td>{{ $att?->clock_in ?: '-' }}</td>
                            <td>{{ $att?->clock_out ?: '-' }}</td>
                            <td class="text-center">{{ $att ? number_format($att->hours_worked, 1).'h' : '-' }}</td>
                            <td>
                                @if($att)
                                    <x-status-badge :status="$att->status" />
                                @else
                                    <span class="badge-default">No Record</span>
                                @endif
                            </td>
                            <td class="text-caption text-gray-400 capitalize">{{ $att->source ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No employees found</p>
                                    <p class="text-caption text-gray-500">No attendance records for the selected date.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
