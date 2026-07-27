<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Salary Structure Details"
            icon="calculator"
            description="{{ $structure->employee->first_name ?? '' }} {{ $structure->employee->last_name ?? '' }} — {{ ucfirst($structure->pay_frequency) }} pay"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.salary-structures.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Salary Structures</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Details</span>
                </nav>
            </x-slot>
            <x-slot name="actions">
                <div class="flex items-center gap-3">
                    @can('update-salary-structure')
                        <a href="{{ route('payroll.salary-structures.edit', $structure) }}" class="btn-secondary inline-flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit
                        </a>
                    @endcan
                    @can('delete-salary-structure')
                        <x-delete-confirm route="{{ route('payroll.salary-structures.destroy', $structure) }}">
                            Delete
                        </x-delete-confirm>
                    @endcan
                </div>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="space-y-6">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50">
                        <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                    </div>
                    <h3 class="text-section font-semibold text-gray-900">Structure Information</h3>
                </div>
            </div>
            <div class="card-body">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Employee</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->employee->first_name ?? '' }} {{ $structure->employee->last_name ?? '' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Employee Number</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->employee->employee_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Basic Salary</dt>
                        <dd class="text-body font-bold text-gray-900">${{ number_format($structure->basic_salary, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Currency</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->currency }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Pay Frequency</dt>
                        <dd class="text-body font-semibold text-gray-900 capitalize">{{ $structure->pay_frequency }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Status</dt>
                        <dd><x-status-badge :status="$structure->is_active ? 'active' : 'inactive'" /></dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Effective From</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->effective_from?->format('M d, Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Effective To</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->effective_to?->format('M d, Y') ?? 'Ongoing' }}</dd>
                    </div>
                    <div>
                        <dt class="text-caption font-medium text-gray-500 mb-1">Organization</dt>
                        <dd class="text-body font-semibold text-gray-900">{{ $structure->organization->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Back Link --}}
        <div class="pt-4">
            <a href="{{ route('payroll.salary-structures.index') }}" class="text-body text-gray-500 hover:text-gray-900 inline-flex items-center gap-2 transition-colors" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to Salary Structures
            </a>
        </div>
    </div>
</x-app-layout>
