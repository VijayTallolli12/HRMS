<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Tenant</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('tenants.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" value="Tenant Name *" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="domain" value="Domain" />
                            <x-text-input id="domain" name="domain" type="text" class="mt-1 block w-full" :value="old('domain')" placeholder="e.g., company.hrms.test" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('tenants.index') }}" class="text-gray-600 hover:text-gray-900" wire:navigate>Cancel</a>
                            <x-primary-button type="submit">Create Tenant</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
