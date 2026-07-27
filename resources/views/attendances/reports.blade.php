<x-app-layout>
    <x-page-header title="Attendance Reports" icon="document-chart-bar">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Reports</span>
        </x-slot>
    </x-page-header>

    <div class="filter-bar">
        <form method="GET" class="filter-bar-inner">
            <div class="filter-group min-w-[180px]">
                <label class="filter-label">Report Type</label>
                <select name="report_type" class="select-field">
                    <option value="summary" {{ $reportType === 'summary' ? 'selected' : '' }}>Summary Report</option>
                    <option value="late" {{ $reportType === 'late' ? 'selected' : '' }}>Late Report</option>
                </select>
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="input-field" required />
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="input-field" required />
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
            @if($reportType !== 'late')
            <div class="filter-group min-w-[180px]">
                <label class="filter-label">Department</label>
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
            <div class="card card-hover">
                <div class="card-header">
                    <h3 class="text-body font-semibold text-gray-900">Late Attendance Report ({{ $dateFrom }} to {{ $dateTo }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Branch</th>
                                <th>Department</th>
                                <th class="text-center">Late Count</th>
                                <th class="text-center">Total Late Minutes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $item)
                                <tr>
                                    <td class="font-medium">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                                    <td>{{ $item['employee']->branch->name ?? '-' }}</td>
                                    <td>{{ $item['employee']->department->name ?? '-' }}</td>
                                    <td class="text-center font-bold text-amber-600">{{ $item['late_count'] }}</td>
                                    <td class="text-center">{{ $item['total_late_minutes'] }} min</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <p class="text-title text-gray-900 mb-1">No late records</p>
                                            <p class="text-caption text-gray-500">No late records found for this period.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card card-hover">
                <div class="card-header">
                    <h3 class="text-body font-semibold text-gray-900">Attendance Summary ({{ $dateFrom }} to {{ $dateTo }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Branch</th>
                                <th class="text-center">Total Days</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Late</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Half Day</th>
                                <th class="text-center">Hours</th>
                                <th class="text-center">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData as $item)
                                <tr>
                                    <td class="font-medium">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                                    <td>{{ $item['employee']->branch->name ?? '-' }}</td>
                                    <td class="text-center">{{ $item['total_days'] }}</td>
                                    <td class="text-center text-emerald-600 font-medium">{{ $item['present'] }}</td>
                                    <td class="text-center text-amber-600">{{ $item['late'] }}</td>
                                    <td class="text-center text-rose-600">{{ $item['absent'] }}</td>
                                    <td class="text-center">{{ $item['half_day'] }}</td>
                                    <td class="text-center">{{ $item['total_hours'] }}h</td>
                                    <td class="text-center">
                                        <span class="badge-{{ $item['attendance_rate'] >= 90 ? 'success' : ($item['attendance_rate'] >= 75 ? 'warning' : 'danger') }}">
                                            {{ $item['attendance_rate'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                            <p class="text-title text-gray-900 mb-1">No data found</p>
                                            <p class="text-caption text-gray-500">Select a date range and click "Generate Report" to view attendance data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @else
        <div class="card p-12">
            <div class="empty-state">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                <p class="text-title text-gray-900 mb-1">No report generated</p>
                <p class="text-caption text-gray-500">Select a date range and click "Generate Report" to view attendance data.</p>
            </div>
        </div>
    @endif
</x-app-layout>
