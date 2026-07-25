<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $employee->full_name }}</h2>
            <div class="flex gap-2">
                @can('update', $employee)
                    <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Edit</a>
                @endcan
                @can('delete', $employee)
                    <x-delete-confirm route="{{ route('employees.destroy', $employee) }}">Delete</x-delete-confirm>
                @endcan
            </div>
        </div>
    </x-slot>

    <div x-data="{ activeTab: 'overview' }">
        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex gap-8 -mb-px">
                <button
                    @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2 transition-colors duration-200"
                    type="button"
                >
                    <x-heroicon name="user" class="w-5 h-5" />
                    Overview
                </button>
                <button
                    @click="activeTab = 'attendance'"
                    :class="activeTab === 'attendance' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2 transition-colors duration-200"
                    type="button"
                >
                    <x-heroicon name="clock" class="w-5 h-5" />
                    Attendance
                </button>
                <button
                    @click="activeTab = 'leave'"
                    :class="activeTab === 'leave' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2 transition-colors duration-200"
                    type="button"
                >
                    <x-heroicon name="calendar" class="w-5 h-5" />
                    Leave
                </button>
                <button
                    @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2 transition-colors duration-200"
                    type="button"
                >
                    <x-heroicon name="document-text" class="w-5 h-5" />
                    Documents
                </button>
            </nav>
        </div>

        {{-- Tab 1: Overview --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="space-y-6">
                {{-- Personal Info Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50">
                            <x-heroicon name="user" class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $employee->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->phone ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Employee Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->employee_number ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Hire Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->hired_at?->format('M d, Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1"><x-status-badge :status="$employee->status ?? 'active'" /></dd>
                        </div>
                    </dl>
                </div>

                {{-- Employment Details Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-50">
                            <x-heroicon name="building-office" class="w-5 h-5 text-emerald-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Employment Details</h3>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Organization</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->organization->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Branch</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->branch->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->department->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Designation</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->designation->title ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Employment Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $employee->employmentType->name ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Tab 2: Attendance --}}
        <div x-show="activeTab === 'attendance'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            @php
                $attendanceRecords = $employee->attendances()
                    ->orderByDesc('date')
                    ->limit(30)
                    ->get();
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($attendanceRecords->isEmpty())
                    <x-empty-state title="No attendance records" description="No attendance records found for this employee." icon="clock" />
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late (min)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($attendanceRecords as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->clock_in ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->clock_out ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$record->status" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->hours_worked ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->late_minutes > 0 ? $record->late_minutes : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab 3: Leave --}}
        <div x-show="activeTab === 'leave'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            @php
                $leaveRecords = \App\Models\Leave::where('employee_id', $employee->id)
                    ->orderByDesc('created_at')
                    ->get();
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if ($leaveRecords->isEmpty())
                    <x-empty-state title="No leave records" description="No leave records found for this employee." icon="calendar" />
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($leaveRecords as $leave)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($leave->leave_type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->start_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->end_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $leave->days }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap"><x-status-badge :status="$leave->status" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('leaves.show', $leave) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Tab 4: Documents --}}
        <div x-show="activeTab === 'documents'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-empty-state title="Coming Soon" description="Document management will be available in a future release." icon="document-text" />
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('employees.index') }}" class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center gap-1">
            <x-heroicon name="arrow-left" class="w-4 h-4" />
            Back to Employees
        </a>
    </div>
</x-app-layout>
