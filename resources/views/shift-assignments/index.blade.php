<x-app-layout>
    <x-page-header title="Shift Assignments" description="Assign work shifts to employees with effective dates." icon="user-group">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Shift Assignments</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-shift-assignment')
                <a href="{{ route('shift-assignments.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Assignment
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('shift-assignments.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search assignments..." />
                </div>
                <div class="filter-group min-w-[180px]">
                    <label class="filter-label">Employee</label>
                    <select name="employee_id" class="select-field">
                        <option value="">All Employees</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group min-w-[180px]">
                    <label class="filter-label">Shift</label>
                    <select name="shift_id" class="select-field">
                        <option value="">All Shifts</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($shiftAssignments->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H9m6-3.07a8.959 8.959 0 00-3.378-.397m-6.453.397a8.959 8.959 0 013.378-.397M9 19.128v-.003c0-1.113.285-2.16.786-3.07m0 0a3.004 3.004 0 012.935-2.935m-5.32 2.935a3.004 3.004 0 01-2.935-2.935M15 7.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No shift assignments found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first shift assignment.</p>
                    @can('create-shift-assignment')
                        <a href="{{ route('shift-assignments.create') }}" class="btn-primary" wire:navigate>Create Assignment</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Shift</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftAssignments as $shiftAssignment)
                                <tr>
                                    <td>
                                        <a href="{{ route('shift-assignments.show', $shiftAssignment) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $shiftAssignment->employee->first_name }} {{ $shiftAssignment->employee->last_name }}</a>
                                    </td>
                                    <td>{{ $shiftAssignment->shift->name }}</td>
                                    <td>{{ $shiftAssignment->effective_from }}</td>
                                    <td>{{ $shiftAssignment->effective_to ?? '—' }}</td>
                                    <td><x-status-badge :status="$shiftAssignment->is_active ? 'active' : 'inactive'" /></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-shift-assignment')
                                                <a href="{{ route('shift-assignments.show', $shiftAssignment) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-shift-assignment')
                                                <a href="{{ route('shift-assignments.edit', $shiftAssignment) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $shiftAssignments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
