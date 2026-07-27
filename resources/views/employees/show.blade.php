<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="{{ $employee->full_name }}"
            icon="user"
            description="{{ $employee->designation?->title ?? 'Employee' }} at {{ $employee->organization?->name ?? 'Organization' }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('employees.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Employees</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">{{ $employee->full_name }}</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    @can('update', $employee)
                        <a href="{{ route('employees.edit', $employee) }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit
                        </a>
                    @endcan
                    @can('delete', $employee)
                        <x-delete-confirm route="{{ route('employees.destroy', $employee) }}">
                            Delete
                        </x-delete-confirm>
                    @endcan
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div x-data="{ activeTab: 'overview' }" class="space-y-6">
        {{-- Profile Header --}}
        <div class="card p-6">
            <div class="flex items-center gap-5">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 text-xl font-bold text-primary-700 border-2 border-primary-100">
                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name ?? '', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h2 class="text-title font-semibold text-gray-900">{{ $employee->full_name }}</h2>
                        <x-status-badge :status="$employee->status ?? 'active'" />
                    </div>
                    <p class="text-body text-gray-500 mt-1">{{ $employee->designation?->title ?? '-' }} {{ $employee->department?->name ? '· ' . $employee->department->name : '' }}</p>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200">
            <nav class="tab-nav flex gap-1">
                <button
                    @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'tab-btn-active' : 'tab-btn-inactive'"
                    class="tab-btn inline-flex items-center gap-2"
                    type="button"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Overview
                </button>
                <button
                    @click="activeTab = 'attendance'"
                    :class="activeTab === 'attendance' ? 'tab-btn-active' : 'tab-btn-inactive'"
                    class="tab-btn inline-flex items-center gap-2"
                    type="button"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Attendance
                </button>
                <button
                    @click="activeTab = 'leave'"
                    :class="activeTab === 'leave' ? 'tab-btn-active' : 'tab-btn-inactive'"
                    class="tab-btn inline-flex items-center gap-2"
                    type="button"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                    Leave
                </button>
                <button
                    @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'tab-btn-active' : 'tab-btn-inactive'"
                    class="tab-btn inline-flex items-center gap-2"
                    type="button"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Documents
                </button>
            </nav>
        </div>

        {{-- Tab 1: Overview --}}
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="space-y-6">
                {{-- Personal Info Card --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                                <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Personal Information</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Full Name</dt>
                                <dd class="text-body font-semibold text-gray-900">{{ $employee->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Email</dt>
                                <dd class="text-body text-gray-900">{{ $employee->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Phone</dt>
                                <dd class="text-body text-gray-900">{{ $employee->phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Employee Number</dt>
                                <dd class="text-body text-gray-900">{{ $employee->employee_number ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Hire Date</dt>
                                <dd class="text-body text-gray-900">{{ $employee->hired_at?->format('M d, Y') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Status</dt>
                                <dd class="mt-0.5"><x-status-badge :status="$employee->status ?? 'active'" /></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Employment Details Card --}}
                <div class="card">
                    <div class="card-header">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                            </div>
                            <h3 class="text-section font-semibold text-gray-900">Employment Details</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Organization</dt>
                                <dd class="text-body text-gray-900">{{ $employee->organization?->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Branch</dt>
                                <dd class="text-body text-gray-900">{{ $employee->branch?->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Department</dt>
                                <dd class="text-body text-gray-900">{{ $employee->department?->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Designation</dt>
                                <dd class="text-body text-gray-900">{{ $employee->designation?->title ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-caption font-medium text-gray-500 mb-1">Employment Type</dt>
                                <dd class="text-body text-gray-900">{{ $employee->employmentType?->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
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
            <div class="card overflow-hidden">
                @if ($attendanceRecords->isEmpty())
                    <x-empty-state
                        icon="clock"
                        title="No attendance records"
                        description="No attendance records found for this employee."
                    />
                @else
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Status</th>
                                    <th>Hours</th>
                                    <th>Late (min)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $record)
                                    <tr>
                                        <td class="text-body font-medium text-gray-900">{{ $record->date->format('M d, Y') }}</td>
                                        <td class="text-body text-gray-500">{{ $record->clock_in ?? '-' }}</td>
                                        <td class="text-body text-gray-500">{{ $record->clock_out ?? '-' }}</td>
                                        <td><x-status-badge :status="$record->status" /></td>
                                        <td class="text-body text-gray-500">{{ $record->hours_worked ?? '-' }}</td>
                                        <td class="text-body text-gray-500">{{ $record->late_minutes > 0 ? $record->late_minutes : '-' }}</td>
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
            <div class="card overflow-hidden">
                @if ($leaveRecords->isEmpty())
                    <x-empty-state
                        icon="calendar"
                        title="No leave records"
                        description="No leave records found for this employee."
                    />
                @else
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveRecords as $leave)
                                    <tr>
                                        <td class="text-body font-medium text-gray-900">{{ ucfirst($leave->leave_type) }}</td>
                                        <td class="text-body text-gray-500">{{ $leave->start_date->format('M d, Y') }}</td>
                                        <td class="text-body text-gray-500">{{ $leave->end_date->format('M d, Y') }}</td>
                                        <td class="text-body text-gray-500">{{ $leave->days }}</td>
                                        <td><x-status-badge :status="$leave->status" /></td>
                                        <td>
                                            <a href="{{ route('leaves.show', $leave) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
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
            <div class="card">
                <x-empty-state
                    icon="document-text"
                    title="Coming Soon"
                    description="Document management will be available in a future release."
                />
            </div>
        </div>
    </div>

    {{-- Back Link --}}
    <div class="mt-8 pt-6 border-t border-gray-100">
        <a href="{{ route('employees.index') }}" class="text-body text-gray-500 hover:text-gray-900 inline-flex items-center gap-2 transition-colors" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Employees
        </a>
    </div>
</x-app-layout>
