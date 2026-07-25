<x-app-layout>
    <x-page-header title="Missing Punches" icon="exclamation-triangle" :breadcrumb="[
        ['label' => 'Attendance Dashboard', 'route' => 'attendances.dashboard'],
        ['label' => 'Missing Punches'],
    ]">
        <x-slot:actions>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('flag-modal').classList.remove('hidden')" class="btn-primary">
                    <x-heroicon name="plus" class="w-4 h-4" />
                    Flag Missing Punch
                </button>
            </div>
        </x-slot:actions>
    </x-page-header>

    @if($pendingCount > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
        <div class="flex items-center gap-3">
            <x-heroicon name="exclamation-triangle" class="w-5 h-5 text-amber-600" />
            <span class="text-sm font-medium text-amber-800">{{ $pendingCount }} missing punch{{ $pendingCount > 1 ? 'es' : '' }} pending resolution.</span>
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="label">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Name or employee code..." />
            </div>
            <div>
                <label class="label">Status</label>
                <select name="status" class="select-field">
                    <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="">All</option>
                </select>
            </div>
            <div>
                <label class="label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
            </div>
            <div>
                <label class="label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="table-header">
                        <th class="table-header-cell">Employee</th>
                        <th class="table-header-cell">Branch</th>
                        <th class="table-header-cell">Date</th>
                        <th class="table-header-cell">Missing</th>
                        <th class="table-header-cell">Detected By</th>
                        <th class="table-header-cell">Status</th>
                        <th class="table-header-cell">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($missingPunches as $mp)
                        <tr class="table-row">
                            <td class="table-cell font-medium">{{ $mp->employee->first_name ?? '' }} {{ $mp->employee->last_name ?? '' }}</td>
                            <td class="table-cell">{{ $mp->employee->branch->name ?? '-' }}</td>
                            <td class="table-cell">{{ $mp->date instanceof \Carbon\Carbon ? $mp->date->format('M d, Y') : $mp->date }}</td>
                            <td class="table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $mp->punch_type === 'missing_in' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $mp->punch_type === 'missing_in' ? 'Missing In' : 'Missing Out' }}
                                </span>
                            </td>
                            <td class="table-cell text-xs text-gray-500 capitalize">{{ $mp->detected_by }}</td>
                            <td class="table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $mp->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ ucfirst($mp->status) }}
                                </span>
                            </td>
                            <td class="table-cell">
                                @if($mp->status === 'pending')
                                    <a href="{{ route('missing-punches.show', $mp) }}" class="text-indigo-600 hover:text-indigo-500 text-xs font-medium" wire:navigate>Resolve</a>
                                @else
                                    <span class="text-xs text-gray-400">{{ $mp->resolved_at?->diffForHumans() ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No missing punches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($missingPunches->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">{{ $missingPunches->links() }}</div>
        @endif
    </div>

    {{-- Flag Modal --}}
    <div id="flag-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Flag Missing Punch</h3>
            <form action="{{ route('missing-punches.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="label">Employee <span class="text-rose-500">*</span></label>
                        <select name="employee_id" class="select-field" required>
                            <option value="">Select employee</option>
                            @foreach(\App\Models\Employee::where('status', 'active')->orderBy('first_name')->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="date" class="input-field" required />
                        </div>
                        <div>
                            <label class="label">Type <span class="text-rose-500">*</span></label>
                            <select name="punch_type" class="select-field" required>
                                <option value="missing_in">Missing Clock In</option>
                                <option value="missing_out">Missing Clock Out</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('flag-modal').classList.add('hidden')" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Flag</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
