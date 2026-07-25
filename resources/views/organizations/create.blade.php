<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Organization</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('organizations.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Organization Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="legal_name" value="Legal Name" />
                            <x-text-input id="legal_name" name="legal_name" type="text" class="mt-1 block w-full" :value="old('legal_name')" />
                            <x-input-error :messages="$errors->get('legal_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tax_id" value="Tax ID" />
                            <x-text-input id="tax_id" name="tax_id" type="text" class="mt-1 block w-full" :value="old('tax_id')" />
                            <x-input-error :messages="$errors->get('tax_id')" class="mt-2" />
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
                            <div>
                                <x-input-label for="address[zip]" value="Zip Code" />
                                <x-text-input id="address[zip]" name="address[zip]" type="text" class="mt-1 block w-full" :value="old('address.zip')" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('organizations.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Organization</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
