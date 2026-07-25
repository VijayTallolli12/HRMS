<x-app-layout>
    <x-page-header title="Payroll Runs" icon="heroicon-o-banknotes">
        @can('create-payroll-runs')
            <x-primary-button tag="a" href="{{ route('payroll.runs.create') }}" wire:navigate>
                <x-heroicon name="heroicon-o-plus" class="h-4 w-4" />
                New Payroll Run
            </x-primary-button>
        @endcan
    </x-page-header>

    <div class="card">
        @if($payrollRuns->isEmpty())
            <x-empty-state icon="heroicon-o-banknotes" title="No payroll runs" description="Create your first payroll run to get started." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Period</th>
                            <th class="table-header text-right">Employees</th>
                            <th class="table-header text-right">Gross</th>
                            <th class="table-header text-right">Deductions</th>
                            <th class="table-header text-right">Net</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payrollRuns as $run)
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($run->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($run->period_end)->format('M d, Y') }}
                                </td>
                                <td class="table-cell text-right text-gray-500">{{ $run->payslips_count ?? $run->payslips->count() }}</td>
                                <td class="table-cell text-right font-medium text-gray-900">${{ number_format($run->gross_total, 2) }}</td>
                                <td class="table-cell text-right text-red-600">${{ number_format($run->deductions_total, 2) }}</td>
                                <td class="table-cell text-right font-medium text-green-600">${{ number_format($run->net_total, 2) }}</td>
                                <td class="table-cell">
                                    <x-status-badge :status="$run->status" />
                                </td>
                                <td class="table-cell text-right">
                                    <a href="{{ route('payroll.runs.show', $run) }}" class="btn-secondary btn-sm" wire:navigate>
                                        <x-heroicon name="heroicon-o-eye" class="h-4 w-4" />
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $payrollRuns->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
