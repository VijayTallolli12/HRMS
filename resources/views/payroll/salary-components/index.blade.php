<x-app-layout>
    <x-page-header title="Salary Components" icon="heroicon-o-cube">
        @can('create-salary-components')
            <x-primary-button tag="a" href="{{ route('payroll.salary-components.create') }}" wire:navigate>
                <x-heroicon name="heroicon-o-plus" class="h-4 w-4" />
                Add Component
            </x-primary-button>
        @endcan
    </x-page-header>

    <div class="card">
        @if($components->isEmpty())
            <x-empty-state icon="heroicon-o-cube" title="No salary components" description="Create salary components to build your payroll structure." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Name</th>
                            <th class="table-header text-left">Code</th>
                            <th class="table-header text-left">Type</th>
                            <th class="table-header text-left">Calculation</th>
                            <th class="table-header text-right">Default Value</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($components as $component)
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">{{ $component->name }}</td>
                                <td class="table-cell text-gray-500 font-mono text-xs">{{ $component->code }}</td>
                                <td class="table-cell">
                                    @if($component->type === 'earning')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700">Earning</span>
                                    @elseif($component->type === 'deduction')
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700">Deduction</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">Benefit</span>
                                    @endif
                                </td>
                                <td class="table-cell text-gray-500 capitalize">{{ $component->calculation_type }}</td>
                                <td class="table-cell text-right text-gray-900">${{ number_format($component->default_value, 2) }}</td>
                                <td class="table-cell">
                                    <x-status-badge :status="$component->is_active ? 'active' : 'inactive'" />
                                </td>
                                <td class="table-cell text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('payroll.salary-components.edit', $component) }}" class="btn-secondary btn-sm" wire:navigate>
                                            <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
                                            Edit
                                        </a>
                                        @can('delete-salary-components')
                                            <x-delete-confirm route="{{ route('payroll.salary-components.destroy', $component) }}" />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $components->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
