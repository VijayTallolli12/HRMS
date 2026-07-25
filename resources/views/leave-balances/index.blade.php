<x-app-layout>
    <x-page-header title="Leave Balances" icon="heroicon-o-clock" />

    <div class="card">
        <div class="p-6 border-b border-gray-100">
            <form method="GET" action="{{ route('leave-balances.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-search-input type="text" name="search" placeholder="Search by employee name..." value="{{ old('search', request('search')) }}" />
                </div>
                <div class="sm:w-48">
                    <select name="leave_type_id" class="select-field" onchange="this.form.submit()">
                        <option value="">All Leave Types</option>
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if($balances->isEmpty())
            <x-empty-state icon="heroicon-o-clock" title="No leave balances" description="Leave balances will appear here once employees are assigned leave types." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="table-header text-left">Employee</th>
                            <th class="table-header text-left">Leave Type</th>
                            <th class="table-header text-right">Entitled</th>
                            <th class="table-header text-right">Taken</th>
                            <th class="table-header text-right">Pending</th>
                            <th class="table-header text-right">Remaining</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($balances as $balance)
                            @php
                                $remaining = $balance->entitled - $balance->taken - $balance->pending;
                            @endphp
                            <tr class="table-row">
                                <td class="table-cell font-medium text-gray-900">{{ $balance->employee->name ?? '-' }}</td>
                                <td class="table-cell text-gray-500">{{ $balance->leaveType->name ?? '-' }}</td>
                                <td class="table-cell text-right text-gray-900">{{ $balance->entitled }}</td>
                                <td class="table-cell text-right text-gray-500">{{ $balance->taken }}</td>
                                <td class="table-cell text-right text-amber-600">{{ $balance->pending }}</td>
                                <td class="table-cell text-right">
                                    <span class="font-medium {{ $remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
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
</x-app-layout>
