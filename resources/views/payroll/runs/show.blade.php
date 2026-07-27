<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Payroll Run Details"
            icon="banknotes"
            description="Period: {{ \Carbon\Carbon::parse($run->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($run->period_end)->format('M d, Y') }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.runs.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Payroll Runs</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Details</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <span class="text-caption font-medium text-gray-500 mr-2">
                        <x-status-badge :status="$run->status" />
                    </span>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary-50">
                        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-caption font-medium text-gray-500">Total Employees</p>
                        <p class="text-title font-bold text-gray-900">{{ $run->payslips->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-sky-50">
                        <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-caption font-medium text-gray-500">Gross Pay</p>
                        <p class="text-title font-bold text-gray-900">${{ number_format($run->total_gross, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-50">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                    </div>
                    <div>
                        <p class="text-caption font-medium text-gray-500">Total Deductions</p>
                        <p class="text-title font-bold text-red-600">${{ number_format($run->total_deductions, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-50">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                    </div>
                    <div>
                        <p class="text-caption font-medium text-gray-500">Net Pay</p>
                        <p class="text-title font-bold text-emerald-600">${{ number_format($run->total_net, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payslips Table --}}
        <div class="card overflow-hidden">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                        <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <h3 class="text-section font-semibold text-gray-900">Payslips</h3>
                </div>
            </div>

            @if($run->payslips->isEmpty())
                <x-empty-state
                    icon="document-text"
                    title="No payslips"
                    description="No payslips have been generated for this run."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="text-right">Basic</th>
                                <th class="text-right">Gross</th>
                                <th class="text-right">Deductions</th>
                                <th class="text-right">Net</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($run->payslips as $payslip)
                                <tr>
                                    <td>
                                        <span class="text-body font-medium text-gray-900">{{ $payslip->employee->first_name ?? '' }} {{ $payslip->employee->last_name ?? '-' }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body text-gray-500">${{ number_format($payslip->basic_salary, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-medium text-gray-900">${{ number_format($payslip->gross_earnings, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body text-red-600">${{ number_format($payslip->total_deductions, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-semibold text-emerald-600">${{ number_format($payslip->net_salary, 2) }}</span>
                                    </td>
                                    <td><x-status-badge :status="$payslip->status" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('payroll.payslips.show', $payslip) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Back Link --}}
        <div class="pt-4">
            <a href="{{ route('payroll.runs.index') }}" class="text-body text-gray-500 hover:text-gray-900 inline-flex items-center gap-2 transition-colors" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Payroll Runs
            </a>
        </div>
    </div>
</x-app-layout>
