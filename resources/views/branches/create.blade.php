<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Branch</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="organization_id" value="Organization *" />
                            <select id="organization_id" name="organization_id" class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                <option value="">Select Organization</option>
                                @foreach ($organizations as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id', $selectedOrgId ?? '') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organization_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="name" value="Branch Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="address[street]" value="Street" />
                                <x-text-input id="address[street]" name="address[street]" type="text" class="mt-1 block w-full" :value="old('address.street')" />
                            </div>
                            <div>
                                <x-input-label for="address[city]" value="City" />
                                <x-text-input id="address[city]" name="address[city]" type="text" class="mt-1 block w-full" :value="old('address.city')" />
                            </div>
                            <div>
                                <x-input-label for="address[state]" value="State" />
                                <x-text-input id="address[state]" name="address[state]" type="text" class="mt-1 block w-full" :value="old('address.state')" />
                            </div>
                            <div>
                                <x-input-label for="address[country]" value="Country" />
                                <x-text-input id="address[country]" name="address[country]" type="text" class="mt-1 block w-full" :value="old('address.country')" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('branches.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Branch</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
