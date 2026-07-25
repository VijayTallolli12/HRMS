<x-app-layout>
    <x-page-header title="Payslip" icon="heroicon-o-document-text">
        <div class="flex items-center gap-3">
            <x-secondary-button tag="a" href="{{ route('payroll.runs.show', $payslip->payrollRun) }}" wire:navigate>
                <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
                Back
            </x-secondary-button>
            <x-primary-button onclick="window.print()">
                <x-heroicon name="heroicon-o-printer" class="h-4 w-4" />
                Print
            </x-primary-button>
        </div>
    </x-page-header>

    <div class="card" id="payslip-content">
        <div class="p-8">
            {{-- Company Header --}}
            <div class="flex items-start justify-between mb-8 pb-6 border-b border-gray-200">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $company->name ?? config('app.name') }}</h2>
                    @if(!empty($company->address))
                        <p class="mt-1 text-sm text-gray-500">{{ $company->address }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <h3 class="text-lg font-semibold text-gray-900">Payslip</h3>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($payslip->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($payslip->period_end)->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- Employee Info --}}
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Employee Information</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="label">Name</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payslip->employee->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="label">Department</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payslip->employee->department->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="label">Designation</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payslip->employee->designation ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="label">Employee Number</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payslip->employee->employee_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Earnings & Deductions --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- Earnings --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Earnings</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Component</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">Basic Salary</td>
                                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">${{ number_format($payslip->basic_salary, 2) }}</td>
                                </tr>
                                @foreach($payslip->earnings as $earning)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $earning->salaryComponent->name ?? $earning->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">${{ number_format($earning->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-4 py-3 text-sm text-gray-900">Total Earnings</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900">${{ number_format($payslip->gross, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Deductions --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Deductions</h4>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Component</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($payslip->deductions as $deduction)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $deduction->salaryComponent->name ?? $deduction->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-red-600">${{ number_format($deduction->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-6 text-sm text-center text-gray-400">No deductions</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50 font-semibold">
                                    <td class="px-4 py-3 text-sm text-gray-900">Total Deductions</td>
                                    <td class="px-4 py-3 text-sm text-right text-red-600">${{ number_format($payslip->deductions, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Net Pay --}}
            <div class="bg-gray-900 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-400">Net Pay</p>
                        <p class="text-3xl font-bold text-white">${{ number_format($payslip->net, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="label text-gray-400">Gross</p>
                        <p class="text-sm font-medium text-white">${{ number_format($payslip->gross, 2) }}</p>
                        <p class="label text-gray-400 mt-2">Deductions</p>
                        <p class="text-sm font-medium text-red-400">-${{ number_format($payslip->deductions, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-lg bg-green-50 border border-green-200 p-4">
            <div class="flex items-center gap-2">
                <x-heroicon name="heroicon-o-check-circle" class="h-5 w-5 text-green-600" />
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</x-app-layout>
