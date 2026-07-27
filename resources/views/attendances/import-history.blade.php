<x-app-layout>
    <x-page-header title="Import History" icon="clock">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('attendances.dashboard') }}" wire:navigate>Attendance</a>
            <span class="breadcrumb-separator">/</span>
            <span>Import History</span>
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('attendances.import.create') }}" class="btn-primary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                New Import
            </a>
        </x-slot>
    </x-page-header>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Batch</th>
                        <th>Filename</th>
                        <th>Type</th>
                        <th>Total</th>
                        <th>Valid</th>
                        <th>Invalid</th>
                        <th>Status</th>
                        <th>Imported By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td class="font-medium">#{{ $batch->id }}</td>
                            <td>{{ $batch->filename }}</td>
                            <td class="uppercase text-caption">{{ $batch->file_type }}</td>
                            <td>{{ $batch->total_rows }}</td>
                            <td class="text-emerald-600 font-medium">{{ $batch->valid_rows }}</td>
                            <td class="{{ $batch->invalid_rows > 0 ? 'text-red-600 font-medium' : '' }}">{{ $batch->invalid_rows }}</td>
                            <td>
                                <span class="badge-{{ $batch->status === 'completed' ? 'success' : ($batch->status === 'preview' ? 'warning' : ($batch->status === 'processing' ? 'info' : 'default')) }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td>{{ $batch->importer->name ?? '-' }}</td>
                            <td class="text-caption text-gray-500">{{ $batch->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    @if($batch->status === 'preview')
                                        <a href="{{ route('attendances.import.preview', $batch) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Preview</a>
                                    @endif
                                    @if($batch->status === 'completed')
                                        <a href="{{ route('attendances.import.results', $batch) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Results</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No import history</p>
                                    <p class="text-caption text-gray-500">Get started by importing your first attendance file.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $batches->links() }}</div>
        @endif
    </div>
</x-app-layout>
