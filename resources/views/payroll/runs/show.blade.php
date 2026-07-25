<x-app-layout>
    <x-page-header title="Payroll Run" icon="heroicon-o-banknotes">
        <x-secondary-button tag="a" href="{{ route('payroll.runs.index') }}" wire:navigate>
            <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
            Back
        </x-secondary-button>
    </x-page-header>

    <div class="mb-6">
        <p class="text-sm text-gray-500">
            Period: {{ \Carbon\Carbon::parse($payrollRun->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payrollRun->period_end)->format('M d, Y') }}
            &middot; <x-status-badge :status="$payrollRun->status" />
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-stat-card title="Total Employees" :value="$payrollRun->payslips_count ?? $payrollRun->payslips->count()" icon="heroicon-o-users" />
        <x-stat-card title="Gross" :value="'$' . number_format($payrollRun->gross_total, 2)" icon="heroicon-o-arrow-trending-up" />
        <x-stat-card title="Deductions" :value="'$' . number_format($payrollRun->deductions_total, 2)" icon="heroicon-o-arrow-trending-down" />
        <x-stat-card title="Net" :value="'$' . number_format($payrollRun->net_total, 2)" icon="heroicon-o-banknotes" />
    </div>

    <div class="card">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Payslips</h3>
        </div>

        @if($payrollRun->payslips->isEmpty())
            <x-empty-state icon="heroicon-o-document-text" title="No payslips" description="No payslips have been generated for this run." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Employee</th>
                            <th class="table-header text-right">Basic</th>
                            <th class="table-header text-right">Gross</th>
                            <th class="table-header text-right">Deductions</th>
                            <th class="table-header text-right">Net</th>
                            <th class="table-header text-left">Status</th>
                            <th class="table-header text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payrollRun->payslips as $payslip)
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">{{ $payslip->employee->name ?? '-' }}</td>
                                <td class="table-cell text-right text-gray-500">${{ number_format($payslip->basic_salary, 2) }}</td>
                                <td class="table-cell text-right font-medium text-gray-900">${{ number_format($payslip->gross, 2) }}</td>
                                <td class="table-cell text-right text-red-600">${{ number_format($payslip->deductions, 2) }}</td>
                                <td class="table-cell text-right font-medium text-green-600">${{ number_format($payslip->net, 2) }}</td>
                                <td class="table-cell">
                                    <x-status-badge :status="$payslip->status" />
                                </td>
                                <td class="table-cell text-right">
                                    <a href="{{ route('payroll.payslips.show', $payslip) }}" class="btn-secondary btn-sm" wire:navigate>
                                        <x-heroicon name="heroicon-o-document-text" class="h-4 w-4" />
                                        View Payslip
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
