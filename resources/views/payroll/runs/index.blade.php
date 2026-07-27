<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Payroll Runs"
            icon="banknotes"
            description="Track and manage all payroll processing runs."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-500">Payroll</span>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Payroll Runs</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                @can('create-payroll-run')
                    <a href="{{ route('payroll.runs.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        New Payroll Run
                    </a>
                @endcan
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="card overflow-hidden">
            @if($runs->isEmpty())
                <x-empty-state
                    icon="banknotes"
                    title="No payroll runs"
                    description="Create your first payroll run to get started."
                >
                    @can('create-payroll-run')
                        <a href="{{ route('payroll.runs.create') }}" class="btn-primary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            New Payroll Run
                        </a>
                    @endcan
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th class="text-right">Employees</th>
                                <th class="text-right">Gross</th>
                                <th class="text-right">Deductions</th>
                                <th class="text-right">Net</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($runs as $run)
                                <tr>
                                    <td>
                                        <span class="text-body font-medium text-gray-900">{{ \Carbon\Carbon::parse($run->period_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($run->period_end)->format('M d, Y') }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body text-gray-500">{{ $run->payslips_count ?? $run->payslips()->count() }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-medium text-gray-900">${{ number_format($run->total_gross, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body text-red-600">${{ number_format($run->total_deductions, 2) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-body font-semibold text-emerald-600">${{ number_format($run->total_net, 2) }}</span>
                                    </td>
                                    <td><x-status-badge :status="$run->status" /></td>
                                    <td class="text-right">
                                        <a href="{{ route('payroll.runs.show', $run) }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors" wire:navigate>View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $runs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
