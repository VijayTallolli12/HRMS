<x-app-layout>
    <x-page-header title="Monthly Attendance Register" icon="calendar-days" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => $monthName],
    ]" />

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="label">Year</label>
                <select name="year" class="select-field">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="label">Month</label>
                <select name="month" class="select-field">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Branch</label>
                <select name="branch_id" class="select-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Department</label>
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

    {{-- Monthly Register Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell sticky left-0 bg-gray-50 z-10 min-w-[180px]">Employee</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php $dayOfWeek = \Carbon\Carbon::createFromDate($year, $month, $d)->dayOfWeek; @endphp
                            <th class="table-header-cell text-center w-8 {{ in_array($dayOfWeek, [0, 6]) ? 'bg-gray-100' : '' }}">{{ $d }}</th>
                        @endfor
                        <th class="table-header-cell text-center bg-indigo-50">P</th>
                        <th class="table-header-cell text-center bg-indigo-50">A</th>
                        <th class="table-header-cell text-center bg-indigo-50">L</th>
                        <th class="table-header-cell text-center bg-indigo-50">Hrs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $row)
                        <tr class="table-row">
                            <td class="table-cell sticky left-0 bg-white z-10">
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
                            <td class="text-center bg-indigo-50 font-bold text-emerald-600">{{ $row['summary']['present'] }}</td>
                            <td class="text-center bg-indigo-50 font-bold text-rose-600">{{ $row['summary']['absent'] }}</td>
                            <td class="text-center bg-indigo-50 font-bold text-amber-600">{{ $row['summary']['late'] }}</td>
                            <td class="text-center bg-indigo-50 font-bold text-gray-900">{{ $row['summary']['total_hours'] }}h</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $daysInMonth + 5 }}" class="px-6 py-12 text-center text-sm text-gray-500">No employee data for the selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
