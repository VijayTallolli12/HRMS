<x-app-layout>
    <x-page-header title="Attendance Reports" icon="document-chart-bar" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Reports'],
    ]" />

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="label">Report Type</label>
                <select name="report_type" class="select-field">
                    <option value="summary" {{ $reportType === 'summary' ? 'selected' : '' }}>Summary Report</option>
                    <option value="late" {{ $reportType === 'late' ? 'selected' : '' }}>Late Report</option>
                </select>
            </div>
            <div>
                <label class="label">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="input-field" required />
            </div>
            <div>
                <label class="label">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="input-field" required />
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
            @if($reportType !== 'late')
            <div>
                <label class="label">Department</label>
                <select name="department_id" class="select-field">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <button type="submit" class="btn-primary">Generate Report</button>
        </form>
    </div>

    @if($reportData)
        @if($reportType === 'late')
            {{-- Late Report --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Late Attendance Report ({{ $dateFrom }} to {{ $dateTo }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="table-header">
                                <th class="table-header-cell">Employee</th>
                                <th class="table-header-cell">Branch</th>
                                <th class="table-header-cell">Department</th>
                                <th class="table-header-cell text-center">Late Count</th>
                                <th class="table-header-cell text-center">Total Late Minutes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reportData as $item)
                                <tr class="table-row">
                                    <td class="table-cell font-medium">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                                    <td class="table-cell">{{ $item['employee']->branch->name ?? '-' }}</td>
                                    <td class="table-cell">{{ $item['employee']->department->name ?? '-' }}</td>
                                    <td class="table-cell text-center font-bold text-amber-600">{{ $item['late_count'] }}</td>
                                    <td class="table-cell text-center">{{ $item['total_late_minutes'] }} min</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No late records found for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- Summary Report --}}
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900">Attendance Summary ({{ $dateFrom }} to {{ $dateTo }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="table-header">
                                <th class="table-header-cell">Employee</th>
                                <th class="table-header-cell">Branch</th>
                                <th class="table-header-cell text-center">Total Days</th>
                                <th class="table-header-cell text-center">Present</th>
                                <th class="table-header-cell text-center">Late</th>
                                <th class="table-header-cell text-center">Absent</th>
                                <th class="table-header-cell text-center">Half Day</th>
                                <th class="table-header-cell text-center">Hours</th>
                                <th class="table-header-cell text-center">Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($reportData as $item)
                                <tr class="table-row">
                                    <td class="table-cell font-medium">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                                    <td class="table-cell">{{ $item['employee']->branch->name ?? '-' }}</td>
                                    <td class="table-cell text-center">{{ $item['total_days'] }}</td>
                                    <td class="table-cell text-center text-emerald-600 font-medium">{{ $item['present'] }}</td>
                                    <td class="table-cell text-center text-amber-600">{{ $item['late'] }}</td>
                                    <td class="table-cell text-center text-rose-600">{{ $item['absent'] }}</td>
                                    <td class="table-cell text-center">{{ $item['half_day'] }}</td>
                                    <td class="table-cell text-center">{{ $item['total_hours'] }}h</td>
                                    <td class="table-cell text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $item['attendance_rate'] >= 90 ? 'bg-emerald-100 text-emerald-700' : ($item['attendance_rate'] >= 75 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                            {{ $item['attendance_rate'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">No data found for the selected period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @else
        <div class="card p-12 text-center">
            <x-heroicon name="document-chart-bar" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
            <p class="text-sm text-gray-500">Select a date range and click "Generate Report" to view attendance data.</p>
        </div>
    @endif
</x-app-layout>
