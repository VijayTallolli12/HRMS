<x-app-layout>
    <x-page-header title="Leave Types" description="Configure and manage leave categories available to employees." icon="tag">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('leaves.index') }}" wire:navigate>Leave Management</a>
            <span class="breadcrumb-separator">/</span>
            <span>Leave Types</span>
        </x-slot>
        <x-slot name="actions">
            @can('create-leave-types')
                <a href="{{ route('leave-types.create') }}" class="btn-primary" wire:navigate>
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Add Leave Type
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="card card-hover">
        @if($leaveTypes->isEmpty())
            <div class="empty-state">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                <p class="text-title text-gray-900 mb-1">No leave types</p>
                <p class="text-caption text-gray-500 mb-4">Create leave types to manage employee time off.</p>
                @can('create-leave-types')
                    <a href="{{ route('leave-types.create') }}" class="btn-primary" wire:navigate>Add Leave Type</a>
                @endcan
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Days Per Year</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveTypes as $leaveType)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $leaveType->name }}</td>
                                <td class="text-right text-gray-500">{{ $leaveType->days_per_year }}</td>
                                <td><x-status-badge :status="$leaveType->is_active ? 'active' : 'inactive'" /></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                            Edit
                                        </a>
                                        @can('delete-leave-types')
                                            <x-delete-confirm route="{{ route('leave-types.destroy', $leaveType) }}" />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $leaveTypes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
