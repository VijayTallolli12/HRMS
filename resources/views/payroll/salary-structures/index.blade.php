<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Salary Structures"
            icon="calculator"
            description="Define and manage employee salary structures."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">Payroll</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Salary Structures</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-salary-structure')
                    <a href="{{ route('payroll.salary-structures.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Add Structure
                    </a>
                @endcan
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="card overflow-hidden">
            @if($structures->isEmpty())
                <x-empty-state
                    icon="calculator"
                    title="No salary structures"
                    description="Define salary structures for your employees."
                >
                    @can('create-salary-structure')
                        <a href="{{ route('payroll.salary-structures.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Add Structure
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="text-right">Basic Salary</th>
                                <th>Currency</th>
                                <th>Pay Frequency</th>
                                <th>Effective From</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($structures as $structure)
                                <tr>
                                    <td>
                                        <a href="{{ route('payroll.salary-structures.show', $structure) }}" class="text-body font-medium text-gray-900 hover:text-primary-600 transition-colors" wire:navigate>
                                            {{ $structure->employee->first_name ?? '' }} {{ $structure->employee->last_name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-medium text-gray-900">${{ number_format($structure->basic_salary, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-body text-gray-500">{{ $structure->currency }}</span>
                                    </td>
                                    <td>
                                        <span class="text-body text-gray-500 capitalize">{{ $structure->pay_frequency }}</span>
                                    </td>
                                    <td>
                                        <span class="text-body text-gray-500">{{ \Carbon\Carbon::parse($structure->effective_from)->format('M d, Y') }}</span>
                                    </td>
                                    <td><x-status-badge :status="$structure->is_active ? 'active' : 'inactive'" /></td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('payroll.salary-structures.show', $structure) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                            <a href="{{ route('payroll.salary-structures.edit', $structure) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $structures->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
