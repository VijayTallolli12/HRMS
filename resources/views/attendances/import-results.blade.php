<x-app-layout>
    <x-page-header title="Import Results" icon="check-circle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.import.history') }}" wire:navigate>Import History</a>
            <span class="breadcrumb-separator">/</span>
            <span>Results Batch #{{ $batch->id }}</span>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl mx-auto">
        <div class="card-body py-12 px-8 text-center">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-title font-bold text-gray-900 mb-2">Import Completed</h3>
            <p class="text-body text-gray-500 mb-8">File: <strong>{{ $batch->filename }}</strong></p>

            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-gray-50 rounded-card">
                    <p class="text-display font-bold text-gray-900">{{ $stats['total'] }}</p>
                    <p class="text-caption text-gray-500 mt-1">Total</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-card">
                    <p class="text-display font-bold text-emerald-600">{{ $stats['processed'] }}</p>
                    <p class="text-caption text-gray-500 mt-1">Processed</p>
                </div>
                <div class="p-4 bg-red-50 rounded-card">
                    <p class="text-display font-bold text-rose-600">{{ $stats['invalid'] }}</p>
                    <p class="text-caption text-gray-500 mt-1">Skipped</p>
                </div>
                <div class="p-4 bg-amber-50 rounded-card">
                    <p class="text-display font-bold text-amber-600">{{ $stats['duplicate'] }}</p>
                    <p class="text-caption text-gray-500 mt-1">Duplicates</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('attendances.daily-register', ['date' => $batch->created_at->format('Y-m-d')]) }}" class="btn-primary" wire:navigate>View Daily Register</a>
                <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>Import More</a>
                <a href="{{ route('attendances.dashboard') }}" class="btn-ghost" wire:navigate>Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
