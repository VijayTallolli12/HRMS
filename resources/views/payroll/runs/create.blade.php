<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create Payroll Run"
            icon="banknotes"
            description="Start a new payroll processing run for your organization."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.runs.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Payroll Runs</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payroll.runs.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="organization_id" value="Organization *" />
                                <x-text-input id="organization_id" name="organization_id" type="number" class="mt-1.5 block w-full" :value="old('organization_id')" required />
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="branch_id" value="Branch" />
                                <x-text-input id="branch_id" name="branch_id" type="number" class="mt-1.5 block w-full" :value="old('branch_id')" />
                                <x-input-error :messages="$errors->get('branch_id')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="period_start" value="Period Start *" />
                                <x-text-input id="period_start" name="period_start" type="date" class="mt-1.5 block w-full" :value="old('period_start')" required />
                                <x-input-error :messages="$errors->get('period_start')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="period_end" value="Period End *" />
                                <x-text-input id="period_end" name="period_end" type="date" class="mt-1.5 block w-full" :value="old('period_end')" required />
                                <x-input-error :messages="$errors->get('period_end')" class="mt-1.5" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('payroll.runs.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Create Payroll Run
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
