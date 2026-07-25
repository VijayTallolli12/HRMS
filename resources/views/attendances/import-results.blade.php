<x-app-layout>
    <x-page-header title="Import Results" icon="check-circle" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Import History', 'route' => 'attendances.import.history'],
        ['label' => 'Results Batch #' . $batch->id],
    ]" />

    <div class="card p-8 max-w-2xl text-center">
        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-emerald-100 mx-auto mb-4">
            <x-heroicon name="check-circle" class="w-8 h-8 text-emerald-600" />
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Import Completed</h3>
        <p class="text-gray-500 mb-6">File: <strong>{{ $batch->filename }}</strong></p>

        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500">Total</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-lg">
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['processed'] }}</p>
                <p class="text-xs text-gray-500">Processed</p>
            </div>
            <div class="p-3 bg-rose-50 rounded-lg">
                <p class="text-2xl font-bold text-rose-600">{{ $stats['invalid'] }}</p>
                <p class="text-xs text-gray-500">Skipped</p>
            </div>
            <div class="p-3 bg-amber-50 rounded-lg">
                <p class="text-2xl font-bold text-amber-600">{{ $stats['duplicate'] }}</p>
                <p class="text-xs text-gray-500">Duplicates</p>
            </div>
        </div>

        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('attendances.daily-register', ['date' => $batch->created_at->format('Y-m-d')]) }}" class="btn-primary" wire:navigate>View Daily Register</a>
            <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>Import More</a>
            <a href="{{ route('attendances.dashboard') }}" class="btn-secondary" wire:navigate>Dashboard</a>
        </div>
    </div>
</x-app-layout>
