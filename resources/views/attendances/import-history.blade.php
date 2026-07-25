<x-app-layout>
    <x-page-header title="Import History" icon="clock" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Import History'],
    ]">
        <x-slot:actions>
            <a href="{{ route('attendances.import.create') }}" class="btn-primary" wire:navigate>
                <x-heroicon name="arrow-up-tray" class="w-4 h-4" />
                New Import
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell">Batch</th>
                        <th class="table-header-cell">Filename</th>
                        <th class="table-header-cell">Type</th>
                        <th class="table-header-cell">Total</th>
                        <th class="table-header-cell">Valid</th>
                        <th class="table-header-cell">Invalid</th>
                        <th class="table-header-cell">Status</th>
                        <th class="table-header-cell">Imported By</th>
                        <th class="table-header-cell">Date</th>
                        <th class="table-header-cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($batches as $batch)
                        <tr class="table-row">
                            <td class="table-cell font-medium">#{{ $batch->id }}</td>
                            <td class="table-cell">{{ $batch->filename }}</td>
                            <td class="table-cell uppercase text-xs">{{ $batch->file_type }}</td>
                            <td class="table-cell">{{ $batch->total_rows }}</td>
                            <td class="table-cell text-emerald-600 font-medium">{{ $batch->valid_rows }}</td>
                            <td class="table-cell {{ $batch->invalid_rows > 0 ? 'text-rose-600 font-medium' : '' }}">{{ $batch->invalid_rows }}</td>
                            <td class="table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $batch->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $batch->status === 'preview' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $batch->status === 'processing' ? 'bg-sky-100 text-sky-700' : '' }}
                                    {{ $batch->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}">
                                    {{ ucfirst($batch->status) }}
                                </span>
                            </td>
                            <td class="table-cell">{{ $batch->importer->name ?? '-' }}</td>
                            <td class="table-cell text-gray-500">{{ $batch->created_at->format('M d, Y H:i') }}</td>
                            <td class="table-cell">
                                <div class="flex gap-2">
                                    @if($batch->status === 'preview')
                                        <a href="{{ route('attendances.import.preview', $batch) }}" class="text-indigo-600 hover:text-indigo-500 text-xs font-medium" wire:navigate>Preview</a>
                                    @endif
                                    @if($batch->status === 'completed')
                                        <a href="{{ route('attendances.import.results', $batch) }}" class="text-indigo-600 hover:text-indigo-500 text-xs font-medium" wire:navigate>Results</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-sm text-gray-500">No import history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $batches->links() }}</div>
        @endif
    </div>
</x-app-layout>
