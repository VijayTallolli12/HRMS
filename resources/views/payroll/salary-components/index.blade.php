<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Salary Components"
            icon="cube"
            description="Manage earning, deduction, and benefit components."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">Payroll</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Salary Components</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-salary-component')
                    <a href="{{ route('payroll.salary-components.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Add Component
                    </a>
                @endcan
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="card overflow-hidden">
            @if($components->isEmpty())
                <x-empty-state
                    icon="cube"
                    title="No salary components"
                    description="Create salary components to build your payroll structure."
                >
                    @can('create-salary-component')
                        <a href="{{ route('payroll.salary-components.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Add Component
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Calculation</th>
                                <th class="text-right">Default Value</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($components as $comp)
                                <tr>
                                    <td>
                                        <span class="text-body font-medium text-gray-900">{{ $comp->name }}</span>
                                    </td>
                                    <td>
                                        <code class="text-caption text-gray-500 bg-gray-50 px-1.5 py-0.5 rounded">{{ $comp->code }}</code>
                                    </td>
                                    <td>
                                        @if($comp->type === 'earning')
                                            <span class="badge-success">Earning</span>
                                        @elseif($comp->type === 'deduction')
                                            <span class="badge-danger">Deduction</span>
                                        @else
                                            <span class="badge-info">Benefit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-body text-gray-500 capitalize">{{ $comp->calculation_type }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-medium text-gray-900">${{ number_format($comp->default_value, 2) }}</span>
                                    </td>
                                    <td><x-status-badge :status="$comp->is_active ? 'active' : 'inactive'" /></td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('payroll.salary-components.edit', $comp) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                            @can('delete-salary-component')
                                                <x-delete-confirm route="{{ route('payroll.salary-components.destroy', $comp) }}">
                                                    Delete
                                                </x-delete-confirm>
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
    </div>
</x-app-layout>
