<x-app-layout>
    <x-slot name="header">
        <x-page-header
            title="Import Complete"
            icon="check-circle"
            description="Employee import has been processed."
        >
            <x-slot name="breadcrumb">
                <nav class="breadcrumb">
                    <a href="{{ route('dashboard') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Home</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <a href="{{ route('employees.index') }}" wire:navigate class="text-primary-600 hover:text-primary-700">Employees</a>
                    <span class="text-gray-400 mx-2">/</span>
                    <span class="text-gray-900">Import Summary</span>
                </nav>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Success Banner --}}
        <div class="card p-6 text-center">
            <div class="flex justify-center mb-4">
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <h2 class="text-title font-semibold text-gray-900 mb-1">Import Successful</h2>
            <p class="text-body text-gray-500">Your employee data has been processed.</p>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card p-4 text-center">
                <p class="text-title font-bold text-emerald-700">{{ $summary['created'] }}</p>
                <p class="text-caption font-medium text-gray-500 mt-1">Created</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-title font-bold text-primary-700">{{ $summary['updated'] }}</p>
                <p class="text-caption font-medium text-gray-500 mt-1">Updated</p>
            </div>
            <div class="card p-4 text-center">
                <p class="text-title font-bold text-amber-700">{{ $summary['skipped'] }}</p>
                <p class="text-caption font-medium text-gray-500 mt-1">Skipped</p>
            </div>
        </div>

        {{-- Errors --}}
        @if (! empty($summary['errors']))
            <div class="card p-4">
                <h3 class="text-section font-semibold text-red-700 mb-2">Errors</h3>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($summary['errors'] as $error)
                        <li class="text-body text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('employees.index') }}" class="btn-primary" wire:navigate>View Employees</a>
        </div>
    </div>
</x-app-layout>
