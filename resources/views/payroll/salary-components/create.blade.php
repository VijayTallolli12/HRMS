<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Create Salary Component"
            icon="cube"
            description="Add a new earning, deduction, or benefit component."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.salary-components.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Salary Components</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Create</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payroll.salary-components.store') }}" method="POST">
                    @csrf

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="name" value="Name *" />
                                <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="code" value="Code *" />
                                <x-text-input id="code" name="code" type="text" class="mt-1.5 block w-full" :value="old('code')" required />
                                <x-input-error :messages="$errors->get('code')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="type" value="Type *" />
                                <select id="type" name="type" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Type</option>
                                    <option value="earning" {{ old('type') === 'earning' ? 'selected' : '' }}>Earning</option>
                                    <option value="deduction" {{ old('type') === 'deduction' ? 'selected' : '' }}>Deduction</option>
                                    <option value="benefit" {{ old('type') === 'benefit' ? 'selected' : '' }}>Benefit</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="calculation_type" value="Calculation Type *" />
                                <select id="calculation_type" name="calculation_type" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Calculation</option>
                                    <option value="fixed" {{ old('calculation_type') === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="percentage" {{ old('calculation_type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                                <x-input-error :messages="$errors->get('calculation_type')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="default_value" value="Default Value *" />
                                <x-text-input id="default_value" name="default_value" type="number" step="0.01" class="mt-1.5 block w-full" :value="old('default_value', '0.00')" required />
                                <x-input-error :messages="$errors->get('default_value')" class="mt-1.5" />
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                    <span class="text-body text-gray-700">Is Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('payroll.salary-components.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Create Component
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
