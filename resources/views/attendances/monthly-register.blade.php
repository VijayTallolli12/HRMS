<x-app-layout>
    <x-page-header title="Monthly Attendance Register" icon="calendar-days">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Monthly Register</span>
        </x-slot>
    </x-page-header>

    <div class="filter-bar">
        <form method="GET" class="filter-bar-inner">
            <div class="filter-group min-w-[120px]">
                <label class="filter-label">Year</label>
                <select name="year" class="select-field">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">Month</label>
                <select name="month" class="select-field">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group min-w-[180px]">
                <label class="filter-label">Branch</label>
                <select name="branch_id" class="select-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group min-w-[180px]">
                <label class="filter-label">Department</label>
                <select name="department_id" class="select-field">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Generate</button>
        </form>
    </div>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table text-xs">
                <thead>
                    <tr>
                        <th class="sticky left-0 bg-gray-50 z-10 min-w-[180px]">Employee</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php $dayOfWeek = \Carbon\Carbon::createFromDate($year, $month, $d)->dayOfWeek; @endphp
                            <th class="text-center w-8 {{ in_array($dayOfWeek, [0, 6]) ? 'bg-gray-100' : '' }}">{{ $d }}</th>
                        @endfor
                        <th class="text-center bg-primary-50">P</th>
                        <th class="text-center bg-primary-50">A</th>
                        <th class="text-center bg-primary-50">L</th>
                        <th class="text-center bg-primary-50">Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            <td class="sticky left-0 bg-white z-10">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900">{{ $row['employee']->first_name }} {{ $row['employee']->last_name }}</span>
                                    <span class="text-gray-400">· {{ $row['employee']->branch->name ?? '' }}</span>
                                </div>
                            </td>
                            @for($d = 1; $d <= $daysInMonth; $d++)
                                @php
                                    $day = $row['daily'][$d] ?? null;
                                    $dayOfWeek = \Carbon\Carbon::createFromDate($year, $month, $d)->dayOfWeek;
                                @endphp
                                <td class="text-center {{ in_array($dayOfWeek, [0, 6]) ? 'bg-gray-50' : '' }}">
                                    @if($day)
                                        <span class="inline-block w-6 h-6 rounded text-[10px] font-bold leading-6
                                            {{ $day['status'] === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $day['status'] === 'late' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $day['status'] === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}
                                            {{ $day['status'] === 'half-day' ? 'bg-sky-100 text-sky-700' : '' }}
                                            {{ $day['status'] === 'remote' ? 'bg-purple-100 text-purple-700' : '' }}"
                                            title="{{ $day['clock_in'] ?? '' }} - {{ $day['clock_out'] ?? '' }}"
                                        >{{ strtoupper(substr($day['status'], 0, 1)) }}</span>
                                    @else
                                        <span class="inline-block w-6 h-6 rounded text-[10px] font-bold leading-6 bg-gray-50 text-gray-300" title="No record">-</span>
                                    @endif
                                </td>
                            @endfor
                            <td class="text-center bg-primary-50 font-bold text-emerald-600">{{ $row['summary']['present'] }}</td>
                            <td class="text-center bg-primary-50 font-bold text-rose-600">{{ $row['summary']['absent'] }}</td>
                            <td class="text-center bg-primary-50 font-bold text-amber-600">{{ $row['summary']['late'] }}</td>
                            <td class="text-center bg-primary-50 font-bold text-gray-900">{{ $row['summary']['total_hours'] }}h</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 5 }}">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No data found</p>
                                    <p class="text-caption text-gray-500">No employee data for the selected filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
