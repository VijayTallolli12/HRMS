<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Edit Salary Structure"
            icon="calculator"
            description="Update salary structure for {{ $structure->employee->first_name ?? '' }} {{ $structure->employee->last_name ?? '' }}"
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('payroll.salary-structures.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Salary Structures</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Edit</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('payroll.salary-structures.update', $structure) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="employee_id" value="Employee *" />
                                <select id="employee_id" name="employee_id" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $structure->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('employee_id')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="basic_salary" value="Basic Salary *" />
                                <x-text-input id="basic_salary" name="basic_salary" type="number" step="0.01" class="mt-1.5 block w-full" :value="old('basic_salary', $structure->basic_salary)" required />
                                <x-input-error :messages="$errors->get('basic_salary')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="currency" value="Currency *" />
                                <select id="currency" name="currency" class="select-field mt-1.5 block w-full" required>
                                    <option value="USD" {{ old('currency', $structure->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                    <option value="EUR" {{ old('currency', $structure->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    <option value="GBP" {{ old('currency', $structure->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                    <option value="INR" {{ old('currency', $structure->currency) === 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                                </select>
                                <x-input-error :messages="$errors->get('currency')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="pay_frequency" value="Pay Frequency *" />
                                <select id="pay_frequency" name="pay_frequency" class="select-field mt-1.5 block w-full" required>
                                    <option value="">Select Frequency</option>
                                    <option value="monthly" {{ old('pay_frequency', $structure->pay_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="biweekly" {{ old('pay_frequency', $structure->pay_frequency) === 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                                    <option value="weekly" {{ old('pay_frequency', $structure->pay_frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                </select>
                                <x-input-error :messages="$errors->get('pay_frequency')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <x-input-label for="effective_from" value="Effective From *" />
                                <x-text-input id="effective_from" name="effective_from" type="date" class="mt-1.5 block w-full" :value="old('effective_from', $structure->effective_from)" required />
                                <x-input-error :messages="$errors->get('effective_from')" class="mt-1.5" />
                            </div>
                            <div>
                                <x-input-label for="effective_to" value="Effective To" />
                                <x-text-input id="effective_to" name="effective_to" type="date" class="mt-1.5 block w-full" :value="old('effective_to', $structure->effective_to)" />
                                <x-input-error :messages="$errors->get('effective_to')" class="mt-1.5" />
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $structure->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                <span class="text-body text-gray-700">Is Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('payroll.salary-structures.index') }}" class="btn-secondary" wire:navigate>Cancel</a>
                        <x-primary-button type="submit" class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            Update Structure
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
