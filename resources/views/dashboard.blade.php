<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = auth()->user();
        $isSuperAdmin = $user->isSuperAdmin();
        $isBranchAdmin = $user->isBranchAdmin();

        if ($isSuperAdmin) {
            $stats = [
                'employees' => \App\Models\Employee::count(),
                'organizations' => \App\Models\Organization::count(),
                'branches' => \App\Models\Branch::count(),
                'today_attendance' => \App\Models\Attendance::whereDate('date', today())->count(),
                'pending_leaves' => \App\Models\Leave::where('status', 'pending')->count(),
            ];
            $recentEmployees = \App\Models\Employee::with(['branch', 'department'])
                ->latest()
                ->limit(5)
                ->get();
        } else {
            $branchId = $user->branch_id;
            $orgId = $user->organization_id;
            $stats = [
                'employees' => \App\Models\Employee::where('branch_id', $branchId)->count(),
                'organizations' => 1,
                'branches' => 1,
                'today_attendance' => \App\Models\Attendance::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
                    ->whereDate('date', today())->count(),
                'pending_leaves' => \App\Models\Leave::whereHas('employee', fn ($q) => $q->where('branch_id', $branchId))
                    ->where('status', 'pending')->count(),
            ];
            $recentEmployees = \App\Models\Employee::with(['branch', 'department'])
                ->where('branch_id', $branchId)
                ->latest()
                ->limit(5)
                ->get();
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">
                        Welcome back, <span class="font-medium text-gray-900">{{ $user->name }}</span>.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $isSuperAdmin ? 'You have full access to all modules.' : 'Managing branch: ' . ($user->branch->name ?? 'N/A') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Total Employees</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['employees'] }}</div>
                </div>

                @if ($isSuperAdmin)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Organizations</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['organizations'] }}</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-sm font-medium text-gray-500">Branches</div>
                        <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['branches'] }}</div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Today's Attendance</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['today_attendance'] }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm font-medium text-gray-500">Pending Leaves</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900">{{ $stats['pending_leaves'] }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-3">
                    @can('create-employee')
                        <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                            Add Employee
                        </a>
                    @endcan
                    <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                        View Employees
                    </a>
                    <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                        View Attendance
                    </a>
                    <a href="{{ route('leaves.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                        View Leaves
                    </a>
                    @if ($isSuperAdmin)
                        <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" wire:navigate>
                            Manage Organizations
                        </a>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Employees</h3>
                </div>
                @if ($recentEmployees->isEmpty())
                    <x-empty-state title="No employees yet" description="Get started by adding your first employee." />
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($recentEmployees as $emp)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <a href="{{ route('employees.show', $emp) }}" class="text-blue-600 hover:text-blue-900" wire:navigate>{{ $emp->first_name }} {{ $emp->last_name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->department->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->branch->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->hired_at?->format('M d, Y') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
