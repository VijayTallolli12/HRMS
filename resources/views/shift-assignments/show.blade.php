<x-app-layout>
    <x-page-header title="Shift Assignment Details" description="View complete information for this shift assignment." icon="user-group">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('shift-assignments.index') }}" wire:navigate>Shift Assignments</a>
            <span class="breadcrumb-separator">/</span>
            <span>Details</span>
        </x-slot>
        <x-slot name="actions">
            @can('update-shift-assignment')
                <a href="{{ route('shift-assignments.edit', $shiftAssignment) }}" class="btn-secondary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    Edit
                </a>
            @endcan
            @can('delete-shift-assignment')
                <x-delete-confirm route="{{ route('shift-assignments.destroy', $shiftAssignment) }}">Delete</x-delete-confirm>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-card bg-primary-50">
                    <svg class="w-5 h-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H9m6-3.07a8.959 8.959 0 00-3.378-.397m-6.453.397a8.959 8.959 0 013.378-.397M9 19.128v-.003c0-1.113.285-2.16.786-3.07m0 0a3.004 3.004 0 012.935-2.935m-5.32 2.935a3.004 3.004 0 01-2.935-2.935M15 7.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <h3 class="text-section font-semibold text-gray-900">Assignment Information</h3>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Employee</dt>
                    <dd class="text-body font-semibold text-gray-900">{{ $shiftAssignment->employee->first_name }} {{ $shiftAssignment->employee->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Shift</dt>
                    <dd class="text-body text-gray-900">{{ $shiftAssignment->shift->name }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Status</dt>
                    <dd><x-status-badge :status="$shiftAssignment->is_active ? 'active' : 'inactive'" /></dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Effective From</dt>
                    <dd class="text-body text-gray-900">{{ $shiftAssignment->effective_from }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Effective To</dt>
                    <dd class="text-body text-gray-900">{{ $shiftAssignment->effective_to ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-caption text-gray-500 mb-1">Notes</dt>
                    <dd class="text-body text-gray-900">{{ $shiftAssignment->notes ?: '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>
