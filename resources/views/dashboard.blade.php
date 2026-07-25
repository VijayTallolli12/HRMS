<x-app-layout>
    {{-- Welcome --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}</h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ $isSuperAdmin ? 'Here\'s what\'s happening across your organization today.' : 'Here\'s what\'s happening in your branch today.' }}
        </p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card
            label="Total Employees"
            :value="$totalEmployees"
            icon="users"
            color="indigo"
            :trend="$employeeTrend"
            trendLabel="vs last month"
        />
        <x-stat-card
            label="Present Today"
            :value="$todayPresent"
            icon="check-circle"
            color="green"
            :trend="$attendanceRate"
            trendLabel="attendance rate"
        />
        <x-stat-card
            label="On Leave"
            :value="$pendingLeaves"
            icon="calendar"
            color="amber"
            :trend="$leaveTrend"
            trendLabel="vs last month"
        />
        @if($isSuperAdmin)
            <x-stat-card
                label="Organizations"
                :value="$totalOrganizations"
                icon="building-office"
                color="purple"
                :trendLabel="$totalBranches . ' branches total'"
            />
        @else
            <x-stat-card
                label="Attendance Rate"
                :value="$attendanceRate . '%'"
                icon="chart-bar"
                color="blue"
                :trend="$attendanceRate"
                trendLabel="today"
            />
        @endif
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <x-chart-card title="Attendance Trend (30 Days)" height="280px">
            <canvas id="attendanceChart"></canvas>
        </x-chart-card>
        <x-chart-card title="Leave Distribution" height="280px">
            <canvas id="leaveChart"></canvas>
        </x-chart-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <x-chart-card title="Headcount by Department" height="280px">
            <canvas id="departmentChart"></canvas>
        </x-chart-card>
        <x-chart-card title="Employees by Status" height="280px">
            <canvas id="statusChart"></canvas>
        </x-chart-card>
    </div>

    {{-- Quick Actions --}}
    <div class="card p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            @can('create-employee')
                <a href="{{ route('employees.create') }}" class="btn-primary" wire:navigate>
                    <x-heroicon name="user-plus" class="w-4 h-4" />
                    Add Employee
                </a>
            @endcan
            <a href="{{ route('attendances.index') }}" class="btn-secondary" wire:navigate>
                <x-heroicon name="clock" class="w-4 h-4" />
                View Attendance
            </a>
            <a href="{{ route('leaves.index') }}" class="btn-secondary" wire:navigate>
                <x-heroicon name="calendar" class="w-4 h-4" />
                View Leaves
            </a>
            @can('view-payroll-run')
                <a href="{{ route('payroll.runs.index') }}" class="btn-secondary" wire:navigate>
                    <x-heroicon name="currency-dollar" class="w-4 h-4" />
                    Payroll
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Recent Employees --}}
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Recent Employees</h3>
                <a href="{{ route('employees.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>View all</a>
            </div>
            @if($recentEmployees->isEmpty())
                <x-empty-state title="No employees yet" description="Start by adding your first employee." icon="users" />
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($recentEmployees as $emp)
                        <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-indigo-600">{{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <a href="{{ route('employees.show', $emp) }}" class="hover:text-indigo-600" wire:navigate>{{ $emp->first_name }} {{ $emp->last_name }}</a>
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ $emp->department->name ?? 'No department' }} &middot; {{ $emp->branch->name ?? '' }}</p>
                            </div>
                            <div class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $emp->hired_at?->diffForHumans() ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Activity --}}
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Recent Activity</h3>
            </div>
            @if($recentActivity->isEmpty())
                <x-empty-state title="No activity yet" description="Activity will appear here as changes are made." icon="bell" />
            @else
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @foreach($recentActivity as $log)
                        <div class="px-6 py-3.5 flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($log->event === 'created')
                                    <div class="h-7 w-7 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <x-heroicon name="plus" class="w-3.5 h-3.5 text-emerald-600" />
                                    </div>
                                @elseif($log->event === 'updated')
                                    <div class="h-7 w-7 rounded-full bg-sky-100 flex items-center justify-center">
                                        <x-heroicon name="pencil" class="w-3.5 h-3.5 text-sky-600" />
                                    </div>
                                @else
                                    <div class="h-7 w-7 rounded-full bg-rose-100 flex items-center justify-center">
                                        <x-heroicon name="trash" class="w-3.5 h-3.5 text-rose-600" />
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">{{ $log->user->name ?? 'System' }}</span>
                                    {{ $log->event }}d a {{ class_basename($log->auditable_type) ?? 'record' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartColors = {
                indigo: 'rgba(99, 102, 241, 0.8)',
                indigoLight: 'rgba(99, 102, 241, 0.1)',
                green: 'rgba(16, 185, 129, 0.8)',
                greenLight: 'rgba(16, 185, 129, 0.1)',
                amber: 'rgba(245, 158, 11, 0.8)',
                rose: 'rgba(244, 63, 94, 0.8)',
                sky: 'rgba(14, 165, 233, 0.8)',
                purple: 'rgba(139, 92, 246, 0.8)',
            };

            const palette = [chartColors.indigo, chartColors.green, chartColors.amber, chartColors.sky, chartColors.rose, chartColors.purple];

            Chart.defaults.font.family = 'Figtree, system-ui, sans-serif';
            Chart.defaults.font.size = 12;

            // Attendance Trend
            const attData = @json($attendanceTrend);
            new Chart(document.getElementById('attendanceChart'), {
                type: 'line',
                data: {
                    labels: attData.labels,
                    datasets: [{
                        label: 'Present',
                        data: attData.present,
                        borderColor: chartColors.indigo,
                        backgroundColor: chartColors.indigoLight,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } }
                    }
                }
            });

            // Leave Distribution
            const leaveData = @json($leaveDistribution);
            new Chart(document.getElementById('leaveChart'), {
                type: 'doughnut',
                data: {
                    labels: leaveData.labels.length ? leaveData.labels : ['No data'],
                    datasets: [{
                        data: leaveData.data.length ? leaveData.data : [1],
                        backgroundColor: palette.slice(0, leaveData.labels.length || 1),
                        borderWidth: 0,
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' } }
                    },
                    cutout: '65%',
                }
            });

            // Headcount by Department
            const deptData = @json($headcountByDepartment);
            new Chart(document.getElementById('departmentChart'), {
                type: 'bar',
                data: {
                    labels: deptData.labels.length ? deptData.labels : ['No data'],
                    datasets: [{
                        label: 'Employees',
                        data: deptData.data.length ? deptData.data : [0],
                        backgroundColor: palette.slice(0, deptData.labels.length || 1),
                        borderRadius: 6,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } }
                    }
                }
            });

            // Employees by Status
            const statusData = @json($headcountByStatus);
            const statusColors = {
                active: chartColors.green,
                inactive: '#9ca3af',
                terminated: chartColors.rose,
                suspended: chartColors.amber,
            };
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels.length ? statusData.labels : ['No data'],
                    datasets: [{
                        data: statusData.data.length ? statusData.data : [1],
                        backgroundColor: statusData.labels.map((l, i) => statusColors[l.toLowerCase()] || palette[i]),
                        borderWidth: 0,
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' } }
                    },
                    cutout: '65%',
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
