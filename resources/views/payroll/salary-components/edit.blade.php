<x-app-layout>
    <x-page-header title="Edit Salary Component" icon="heroicon-o-cube">
        <x-secondary-button tag="a" href="{{ route('payroll.salary-components.index') }}" wire:navigate>
            <x-heroicon name="heroicon-o-arrow-left" class="h-4 w-4" />
            Back
        </x-secondary-button>
    </x-page-header>

    <div class="card p-6">
        <form action="{{ route('payroll.salary-components.update', $component) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="name" value="Name" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $component->name)" required autofocus />
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="code" value="Code" />
                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" :value="old('code', $component->code)" required />
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="type" value="Type" />
                    <select id="type" name="type" class="select-field mt-1 block w-full" required>
                        <option value="">Select Type</option>
                        <option value="earning" {{ old('type', $component->type) === 'earning' ? 'selected' : '' }}>Earning</option>
                        <option value="deduction" {{ old('type', $component->type) === 'deduction' ? 'selected' : '' }}>Deduction</option>
                        <option value="benefit" {{ old('type', $component->type) === 'benefit' ? 'selected' : '' }}>Benefit</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="calculation_type" value="Calculation Type" />
                    <select id="calculation_type" name="calculation_type" class="select-field mt-1 block w-full" required>
                        <option value="">Select Calculation</option>
                        <option value="fixed" {{ old('calculation_type', $component->calculation_type) === 'fixed' ? 'selected' : '' }}>Fixed</option>
                        <option value="percentage" {{ old('calculation_type', $component->calculation_type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                    @error('calculation_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-input-label for="default_value" value="Default Value" />
                    <x-text-input id="default_value" name="default_value" type="number" step="0.01" class="mt-1 block w-full" :value="old('default_value', $component->default_value)" required />
                    @error('default_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $component->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" />
                        <span class="label">Is Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <x-secondary-button tag="a" href="{{ route('payroll.salary-components.index') }}" wire:navigate>Cancel</x-secondary-button>
                <x-primary-button type="submit">Update Component</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
