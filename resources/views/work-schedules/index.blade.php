<x-app-layout>
    <x-page-header title="Work Schedules" description="Define working hours, days, and break policies for each organization." icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Work Schedules</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-work-schedule')
                <a href="{{ route('work-schedules.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create Schedule
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form action="{{ route('work-schedules.index') }}" method="GET" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Search work schedules..." />
                </div>
                <div class="filter-group min-w-[180px]">
                    <label class="filter-label">Organization</label>
                    <select name="organization_id" class="select-field">
                        <option value="">All Organizations</option>
                        @foreach ($organizations as $organization)
                            <option value="{{ $organization->id }}" {{ request('organization_id') == $organization->id ? 'selected' : '' }}>{{ $organization->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
            </form>
        </div>

        <div class="card card-hover">
            @if ($workSchedules->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No work schedules found</p>
                    <p class="text-caption text-gray-500 mb-4">Get started by creating your first work schedule.</p>
                    @can('create-work-schedule')
                        <a href="{{ route('work-schedules.create') }}" class="btn-primary" wire:navigate>Create Schedule</a>
                    @endcan
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Organization</th>
                                <th>Hours/Day</th>
                                <th>Break (min)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workSchedules as $workSchedule)
                                <tr>
                                    <td>
                                        <a href="{{ route('work-schedules.show', $workSchedule) }}" class="text-primary-600 hover:text-primary-700 font-medium" wire:navigate>{{ $workSchedule->name }}</a>
                                    </td>
                                    <td>{{ $workSchedule->organization->name }}</td>
                                    <td>{{ $workSchedule->hours_per_day }}</td>
                                    <td>{{ $workSchedule->break_minutes }}</td>
                                    <td><x-status-badge :status="$workSchedule->is_active ? 'active' : 'inactive'" /></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            @can('view-work-schedule')
                                                <a href="{{ route('work-schedules.show', $workSchedule) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>View</a>
                                            @endcan
                                            @can('update-work-schedule')
                                                <a href="{{ route('work-schedules.edit', $workSchedule) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Edit</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $workSchedules->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
