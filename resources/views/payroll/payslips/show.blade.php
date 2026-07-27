<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Payslip"
            icon="document-text"
            description="{{ $payslip->employee->first_name ?? '' }} {{ $payslip->employee->last_name ?? '-' }} — {{ \Carbon\Carbon::parse($run->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($run->period_end)->format('M d, Y') }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.runs.show', $run) }}" wire:navigate class="text-primary-600 hover:text-primary-700">Payroll Run</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Payslip</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    <a href="{{ route('payroll.runs.show', $run) }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                        Back
                    </a>
                    <button onclick="window.print()" class="btn-primary inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>
                        Print
                    </button>
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6" id="payslip-content">
        {{-- Company Header --}}
        <div class="card">
            <div class="card-body">
                <div class="flex items-start justify-between pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-title font-bold text-gray-900">{{ $branding['company_name'] }}</h2>
                        <p class="text-caption text-gray-500 mt-1">Payslip</p>
                    </div>
                    <div class="text-right">
                        <p class="text-body font-medium text-gray-900">{{ \Carbon\Carbon::parse($run->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($run->period_end)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employee Info --}}
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                        <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    </div>
                    <h3 class="text-section font-semibold text-gray-900">Employee Information</h3>
                </div>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Name</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $payslip->employee->first_name ?? '' }} {{ $payslip->employee->last_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Department</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $payslip->employee->department->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Designation</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $payslip->employee->designation->title ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Employee Number</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $payslip->employee->employee_number ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Earnings & Deductions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Earnings --}}
            <div class="card overflow-hidden">
                <div class="card-header bg-emerald-50 border-b border-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-100">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-section font-semibold text-emerald-900">Earnings</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium">Basic Salary</td>
                                <td class="text-right font-semibold">${{ number_format($payslip->basic_salary, 2) }}</td>
                            </tr>
                            @foreach($payslip->items->where('type', 'earning') as $earning)
                                <tr>
                                    <td>{{ $earning->component_name }}</td>
                                    <td class="text-right font-medium">${{ number_format($earning->amount, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50">
                                <td class="font-bold">Total Earnings</td>
                                <td class="text-right font-bold text-gray-900">${{ number_format($payslip->gross_earnings, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Deductions --}}
            <div class="card overflow-hidden">
                <div class="card-header bg-red-50 border-b border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-100">
                            <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                        </div>
                        <h3 class="text-section font-semibold text-red-900">Deductions</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payslip->items->where('type', 'deduction') as $deduction)
                                <tr>
                                    <td>{{ $deduction->component_name }}</td>
                                    <td class="text-right font-medium text-red-600">${{ number_format($deduction->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-gray-400 py-6">No deductions</td>
                                </tr>
                            @endforelse
                            <tr class="bg-gray-50">
                                <td class="font-bold">Total Deductions</td>
                                <td class="text-right font-bold text-red-600">${{ number_format($payslip->total_deductions, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Net Pay --}}
        <div class="bg-gray-900 rounded-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption font-medium text-gray-400">Net Pay</p>
                    <p class="text-display font-bold text-white">${{ number_format($payslip->net_salary, 2) }}</p>
                </div>
                <div class="text-right space-y-2">
                    <div>
                        <p class="text-caption font-medium text-gray-400">Gross</p>
                        <p class="text-body font-semibold text-white">${{ number_format($payslip->gross_earnings, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-caption font-medium text-gray-400">Deductions</p>
                        <p class="text-body font-semibold text-red-400">-${{ number_format($payslip->total_deductions, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-card p-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-body font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
