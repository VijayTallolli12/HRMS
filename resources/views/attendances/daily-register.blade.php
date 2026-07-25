<x-app-layout>
    <x-page-header title="Daily Attendance Register" icon="clipboard-document-list" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Daily Register'],
    ]" />

    {{-- Date Navigation --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="label">Date</label>
                <input type="date" name="date" value="{{ $date }}" class="input-field" />
            </div>
            <div>
                <label class="label">Branch</label>
                <select name="branch_id" class="select-field">
                    <option value="">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($branchId ?? '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">View</button>
            <div class="flex gap-2 ml-auto">
                @php
                    $prevDate = \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d');
                    $nextDate = \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d');
                @endphp
                <a href="{{ route('attendances.daily-register', ['date' => $prevDate, 'branch_id' => $branchId]) }}" class="btn-secondary px-3" wire:navigate>
                    <x-heroicon name="chevron-left" class="w-4 h-4" />
                </a>
                <span class="px-3 py-2 text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
                <a href="{{ route('attendances.daily-register', ['date' => $nextDate, 'branch_id' => $branchId]) }}" class="btn-secondary px-3" wire:navigate>
                    <x-heroicon name="chevron-right" class="w-4 h-4" />
                </a>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $employees->count() }}</p>
            <p class="text-xs text-gray-500">Total Employees</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $presentCount }}</p>
            <p class="text-xs text-gray-500">Present</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-rose-600">{{ $absentCount }}</p>
            <p class="text-xs text-gray-500">Absent</p>
        </div>
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $lateCount }}</p>
            <p class="text-xs text-gray-500">Late</p>
        </div>
    </div>

    {{-- Register Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell">Employee</th>
                        <th class="table-header-cell">Department</th>
                        <th class="table-header-cell">Clock In</th>
                        <th class="table-header-cell">Clock Out</th>
                        <th class="table-header-cell text-center">Hours</th>
                        <th class="table-header-cell">Status</th>
                        <th class="table-header-cell">Source</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $emp)
                        @php $att = $emp->attendances->first(); @endphp
                        <tr class="table-row">
                            <td class="table-cell font-medium">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-xs font-bold text-indigo-600">{{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                        <p class="text-xs text-gray-400">{{ $emp->employee_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell text-gray-500">{{ $emp->department->name ?? '-' }}</td>
                            <td class="table-cell">{{ $att?->clock_in ?: '-' }}</td>
                            <td class="table-cell">{{ $att?->clock_out ?: '-' }}</td>
                            <td class="table-cell text-center">{{ $att ? number_format($att->hours_worked, 1).'h' : '-' }}</td>
                            <td class="table-cell">
                                @if($att)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $att->status === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $att->status === 'late' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $att->status === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}
                                        {{ $att->status === 'half-day' ? 'bg-sky-100 text-sky-700' : '' }}
                                        {{ $att->status === 'remote' ? 'bg-purple-100 text-purple-700' : '' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">No Record</span>
                                @endif
                            </td>
                            <td class="table-cell text-xs text-gray-400 capitalize">{{ $att->source ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
