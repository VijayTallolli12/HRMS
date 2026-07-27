<x-app-layout>
    <x-page-header title="Missing Punches" description="Track and resolve missing clock-in and clock-out records." icon="exclamation-triangle">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Missing Punches</span>
        </x-slot>
        <x-slot name="actions">
            <button type="button" onclick="document.getElementById('flag-modal').classList.remove('hidden')" class="btn-primary">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Flag Missing Punch
            </button>
        </x-slot>
    </x-page-header>

    @if($pendingCount > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-card p-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            <span class="text-body font-medium text-amber-800">{{ $pendingCount }} missing punch{{ $pendingCount > 1 ? 'es' : '' }} pending resolution.</span>
        </div>
    </div>
    @endif

    <div class="filter-bar">
        <form method="GET" class="filter-bar-inner">
            <div class="filter-group flex-1 min-w-[200px]">
                <label class="filter-label">Search Employee</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Name or employee code..." />
            </div>
            <div class="filter-group min-w-[140px]">
                <label class="filter-label">Status</label>
                <select name="status" class="select-field">
                    <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="">All</option>
                </select>
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="input-field" />
            </div>
            <div class="filter-group min-w-[160px]">
                <label class="filter-label">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="input-field" />
            </div>
            <button type="submit" class="btn-primary">Filter</button>
        </form>
    </div>

    <div class="card card-hover">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Missing</th>
                        <th>Detected By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missingPunches as $mp)
                        <tr>
                            <td>
                                <span class="font-medium text-gray-900">{{ $mp->employee->first_name ?? '' }} {{ $mp->employee->last_name ?? '' }}</span>
                            </td>
                            <td>{{ $mp->employee->branch->name ?? '-' }}</td>
                            <td>{{ $mp->date instanceof \Carbon\Carbon ? $mp->date->format('M d, Y') : $mp->date }}</td>
                            <td>
                                <span class="badge-{{ $mp->punch_type === 'missing_in' ? 'danger' : 'warning' }}">
                                    {{ $mp->punch_type === 'missing_in' ? 'Missing In' : 'Missing Out' }}
                                </span>
                            </td>
                            <td class="text-caption text-gray-500 capitalize">{{ $mp->detected_by }}</td>
                            <td>
                                <span class="badge-{{ $mp->status === 'pending' ? 'warning' : 'success' }}">
                                    {{ ucfirst($mp->status) }}
                                </span>
                            </td>
                            <td>
                                @if($mp->status === 'pending')
                                    <a href="{{ route('missing-punches.show', $mp) }}" class="btn-ghost text-xs px-2.5 py-1" wire:navigate>Resolve</a>
                                @else
                                    <span class="text-caption text-gray-400">{{ $mp->resolved_at?->diffForHumans() ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" /></svg>
                                    <p class="text-title text-gray-900 mb-1">No missing punches found</p>
                                    <p class="text-caption text-gray-500">All punch records are complete.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($missingPunches->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $missingPunches->links() }}</div>
        @endif
    </div>

    <div id="flag-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-card shadow-xl w-full max-w-md">
            <div class="px-6 py-5 border-b border-gray-100">
                <h3 class="text-section font-semibold text-gray-900">Flag Missing Punch</h3>
                <p class="text-caption text-gray-500 mt-1">Report a missing clock-in or clock-out record.</p>
            </div>
            <form action="{{ route('missing-punches.store') }}" method="POST">
                @csrf
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="label">Employee <span class="text-red-500">*</span></label>
                        <select name="employee_id" class="select-field" required>
                            <option value="">Select employee</option>
                            @foreach(\App\Models\Employee::where('status', 'active')->orderBy('first_name')->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="date" class="input-field" required />
                        </div>
                        <div>
                            <label class="label">Type <span class="text-red-500">*</span></label>
                            <select name="punch_type" class="select-field" required>
                                <option value="missing_in">Missing Clock In</option>
                                <option value="missing_out">Missing Clock Out</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('flag-modal').classList.add('hidden')" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary">Flag</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
