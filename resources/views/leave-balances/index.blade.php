<x-app-layout>
    <x-page-header title="Leave Balances" description="View and track employee leave entitlements, usage, and remaining balances." icon="calculator">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('leaves.index') }}" wire:navigate>Leave Management</a>
            <span class="breadcrumb-separator">/</span>
            <span>Leave Balances</span>
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="filter-bar">
            <form method="GET" action="{{ route('leave-balances.index') }}" class="filter-bar-inner">
                <div class="filter-group flex-1 min-w-[200px]">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" class="input-field" placeholder="Search by employee name..." value="{{ old('search', request('search')) }}" />
                </div>
                <div class="filter-group min-w-[180px]">
                    <label class="filter-label">Leave Type</label>
                    <select name="leave_type_id" class="select-field" onchange="this.form.submit()">
                        <option value="">All Leave Types</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="card card-hover">
            @if($balances->isEmpty())
                <div class="empty-state">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-title text-gray-900 mb-1">No leave balances</p>
                    <p class="text-caption text-gray-500">Leave balances will appear here once employees are assigned leave types.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th class="text-right">Entitled</th>
                                <th class="text-right">Taken</th>
                                <th class="text-right">Pending</th>
                                <th class="text-right">Remaining</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($balances as $balance)
                                @php
                                    $remaining = $balance->entitled - $balance->taken - $balance->pending;
                                @endphp
                                <tr>
                                    <td class="font-medium text-gray-900">{{ $balance->employee->name ?? '-' }}</td>
                                    <td class="text-gray-500">{{ $balance->leaveType->name ?? '-' }}</td>
                                    <td class="text-right text-gray-900">{{ $balance->entitled }}</td>
                                    <td class="text-right text-gray-500">{{ $balance->taken }}</td>
                                    <td class="text-right">
                                        <span class="badge-warning">{{ $balance->pending }}</span>
                                    </td>
                                    <td class="text-right">
                                        <span class="font-semibold {{ $remaining > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            {{ $remaining }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $balances->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
