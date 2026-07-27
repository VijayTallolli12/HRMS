<x-app-layout>
    {{-- Welcome Section --}}
    <div class="page-header mb-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-display font-bold text-gray-900 tracking-tight">Welcome back, {{ $user->name }}</h2>
                <p class="mt-2 text-body text-gray-500">
                    {{ $isSuperAdmin ? "Here's what's happening across your organization today." : "Here's what's happening in your branch today." }}
                </p>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
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

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
        <x-chart-card title="Attendance Trend" height="280px">
            <canvas id="attendanceChart"></canvas>
        </x-chart-card>
        <x-chart-card title="Leave Distribution" height="280px">
            <canvas id="leaveChart"></canvas>
        </x-chart-card>
    </div>

    {{-- Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
        <x-chart-card title="Headcount by Department" height="280px">
            <canvas id="departmentChart"></canvas>
        </x-chart-card>
        <x-chart-card title="Employees by Status" height="280px">
            <canvas id="statusChart"></canvas>
        </x-chart-card>
    </div>

    {{-- Bottom Row: Recent Employees + Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Recent Employees --}}
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-section font-semibold text-gray-900">Recent Employees</h3>
                <a href="{{ route('employees.index') }}" class="text-caption font-medium text-primary-600 hover:text-primary-600/80 transition-colors" wire:navigate>View all
                    <svg class="inline-block w-3.5 h-3.5 ml-0.5 -mt-px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentEmployees->isEmpty())
                    <x-empty-state title="No employees yet" description="Start by adding your first employee." icon="users" />
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($recentEmployees as $emp)
                            <a href="{{ route('employees.show', $emp) }}" wire:navigate class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/60 transition-all duration-150 group">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center ring-2 ring-white group-hover:ring-primary-100 transition-all">
                                    <span class="text-caption font-bold text-primary-600">{{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-body font-medium text-gray-900 truncate group-hover:text-primary-600 transition-colors">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                    <p class="text-caption text-gray-400 truncate">{{ $emp->department->name ?? 'No department' }}{{ $emp->branch->name ? ' · ' . $emp->branch->name : '' }}</p>
                                </div>
                                <div class="flex-shrink-0 text-micro text-gray-400 whitespace-nowrap">
                                    {{ $emp->hired_at?->diffForHumans() ?? '' }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card">
            <div class="card-header">
                <h3 class="text-section font-semibold text-gray-900">Recent Activity</h3>
            </div>
            <div class="card-body p-0">
                @if($recentActivity->isEmpty())
                    <x-empty-state title="No activity yet" description="Activity will appear here as changes are made." icon="bell" />
                @else
                    <div class="max-h-96 overflow-y-auto">
                        <div class="px-6 py-2">
                            @foreach($recentActivity as $log)
                                @php
                                    $eventColor = match($log->event) {
                                        'created' => ['dot' => 'bg-emerald-400', 'ring' => 'bg-emerald-50'],
                                        'updated' => ['dot' => 'bg-sky-400', 'ring' => 'bg-sky-50'],
                                        default => ['dot' => 'bg-red-400', 'ring' => 'bg-red-50'],
                                    };
                                @endphp
                                <div class="flex gap-3 {{ !$loop->last ? 'pb-4' : '' }}">
                                    {{-- Timeline column --}}
                                    <div class="flex flex-col items-center">
                                        <div class="flex-shrink-0 h-2.5 w-2.5 rounded-full {{ $eventColor['dot'] }} ring-4 {{ $eventColor['ring'] }} ring-opacity-50"></div>
                                        @if(!$loop->last)
                                            <div class="w-px flex-1 bg-gray-100 mt-1"></div>
                                        @endif
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0 pb-0">
                                        <p class="text-body text-gray-700 leading-relaxed">
                                            <span class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</span>
                                            {{ $log->event }}d a
                                            <span class="font-medium text-gray-900">{{ class_basename($log->auditable_type) ?? 'record' }}</span>
                                        </p>
                                        <p class="text-micro text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
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

            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
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
