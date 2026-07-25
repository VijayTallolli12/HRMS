<x-app-layout>
    <x-page-header title="Leave Types" icon="heroicon-o-calendar">
        @can('create-leave-types')
            <x-primary-button tag="a" href="{{ route('leave-types.create') }}" wire:navigate>
                <x-heroicon name="heroicon-o-plus" class="h-4 w-4" />
                Add Leave Type
            </x-primary-button>
        @endcan
    </x-page-header>

    <div class="card">
        @if($leaveTypes->isEmpty())
            <x-empty-state icon="heroicon-o-calendar" title="No leave types" description="Create leave types to manage employee time off." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Name</th>
                            <th class="table-header text-right">Days Per Year</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($leaveTypes as $leaveType)
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">{{ $leaveType->name }}</td>
                                <td class="table-cell text-right text-gray-500">{{ $leaveType->days_per_year }}</td>
                                <td class="table-cell">
                                    <x-status-badge :status="$leaveType->is_active ? 'active' : 'inactive'" />
                                </td>
                                <td class="table-cell text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn-secondary btn-sm" wire:navigate>
                                            <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
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
