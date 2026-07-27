<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Settings"
            icon="cog-6-tooth"
            description="Manage your organization settings and preferences."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Settings</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <livewire:settings-form />
</x-app-layout>
