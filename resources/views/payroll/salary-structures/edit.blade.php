<x-app-layout>
    <x-page-header title="Edit Salary Structure" icon="heroicon-o-calculator">
        <x-secondary-button tag="a" href="{{ route('payroll.salary-structures.index') }}" wire:navigate>
            <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
            Back
        </x-secondary-button>
    </x-page-header>

    <div class="card p-6">
        <form action="{{ route('payroll.salary-structures.update', $structure) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="employee_id" value="Employee" />
                    <select id="employee_id" name="employee_id" class="select-field mt-1 block w-full" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $structure->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="basic_salary" value="Basic Salary" />
                    <x-text-input id="basic_salary" name="basic_salary" type="number" step="0.01" class="mt-1 block w-full" :value="old('basic_salary', $structure->basic_salary)" required />
                    @error('basic_salary')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="currency" value="Currency" />
                    <select id="currency" name="currency" class="select-field mt-1 block w-full" required>
                        <option value="USD" {{ old('currency', $structure->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', $structure->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ old('currency', $structure->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                        <option value="INR" {{ old('currency', $structure->currency) === 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="pay_frequency" value="Pay Frequency" />
                    <select id="pay_frequency" name="pay_frequency" class="select-field mt-1 block w-full" required>
                        <option value="">Select Frequency</option>
                        <option value="monthly" {{ old('pay_frequency', $structure->pay_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="biweekly" {{ old('pay_frequency', $structure->pay_frequency) === 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                        <option value="weekly" {{ old('pay_frequency', $structure->pay_frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    </select>
                    @error('pay_frequency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="effective_from" value="Effective From" />
                    <x-text-input id="effective_from" name="effective_from" type="date" class="mt-1 block w-full" :value="old('effective_from', $structure->effective_from)" required />
                    @error('effective_from')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="effective_to" value="Effective To (Optional)" />
                    <x-text-input id="effective_to" name="effective_to" type="date" class="mt-1 block w-full" :value="old('effective_to', $structure->effective_to)" />
                    @error('effective_to')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $structure->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <span class="label">Is Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <x-secondary-button tag="a" href="{{ route('payroll.salary-structures.index') }}" wire:navigate>Cancel</x-secondary-button>
                <x-primary-button type="submit">Update Structure</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
