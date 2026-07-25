<x-app-layout>
    <x-page-header title="Salary Structures" icon="heroicon-o-calculator">
        @can('create-salary-structures')
            <x-primary-button tag="a" href="{{ route('payroll.salary-structures.create') }}" wire:navigate>
                <x-heroicon name="heroicon-o-plus" class="h-4 w-4" />
                Add Structure
            </x-primary-button>
        @endcan
    </x-page-header>

    <div class="card">
        @if($structures->isEmpty())
            <x-empty-state icon="heroicon-o-calculator" title="No salary structures" description="Define salary structures for your employees." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Employee</th>
                            <th class="table-header text-right">Basic Salary</th>
                            <th class="table-header text-left">Currency</th>
                            <th class="table-header text-left">Pay Frequency</th>
                            <th class="table-header text-left">Effective From</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($structures as $structure)
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">{{ $structure->employee->name ?? '-' }}</td>
                                <td class="table-cell text-right font-medium text-gray-900">${{ number_format($structure->basic_salary, 2) }}</td>
                                <td class="table-cell text-gray-500">{{ $structure->currency }}</td>
                                <td class="table-cell text-gray-500 capitalize">{{ $structure->pay_frequency }}</td>
                                <td class="table-cell text-gray-500">{{ \Carbon\Carbon::parse($structure->effective_from)->format('M d, Y') }}</td>
                                <td class="table-cell">
                                    <x-status-badge :status="$structure->is_active ? 'active' : 'inactive'" />
                                </td>
                                <td class="table-cell text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('payroll.salary-structures.edit', $structure) }}" class="btn-secondary btn-sm" wire:navigate>
                                            <x-heroicon name="heroicon-o-pencil" class="h-4 w-4" />
                                            Edit
                                        </a>
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
</x-app-layout>
