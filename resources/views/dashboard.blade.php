<x-app-layout>
    {{-- Header & Quick Action Center --}}
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 p-6 sm:p-8 rounded-2xl text-white shadow-xl relative overflow-hidden">
            {{-- Background decorative grid --}}
            <div class="absolute inset-0 bg-[radial-gradient(#6366f1_1px,transparent_1px)] [background-size:16px_16px] opacity-10 pointer-events-none"></div>

            <div class="relative z-10">
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                @endphp
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-xs font-medium text-indigo-200 backdrop-blur-xs mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ now()->format('l, F j, Y') }}</span>
                    <span>·</span>
                    <span>{{ $isSuperAdmin ? 'Enterprise Scope' : ($user->branch?->name ?? 'Branch Scope') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    {{ $greeting }}, {{ $user->name }}
                </h1>
                <p class="mt-1.5 text-sm text-slate-300 max-w-xl leading-relaxed">
                    {{ $isSuperAdmin ? 'Real-time overview of headcount, attendance, leaves, and operations across all organizations.' : "Real-time overview of workforce attendance and operations for your branch." }}
                </p>
            </div>

            {{-- Quick Action Center --}}
            <div class="relative z-10 flex flex-wrap items-center gap-2.5 sm:self-start lg:self-center">
                @can('create-employee')
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-btn bg-indigo-500 hover:bg-indigo-600 text-white shadow-sm transition-all duration-150" wire:navigate>
                        <x-heroicon name="user-plus" class="w-4 h-4 text-indigo-100" />
                        <span>Add Employee</span>
                    </a>
                @endcan

                @can('view-attendance')
                    <a href="{{ route('attendances.daily-register') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-btn bg-white/10 hover:bg-white/20 text-white border border-white/10 backdrop-blur-xs transition-all duration-150" wire:navigate>
                        <x-heroicon name="calendar-days" class="w-4 h-4 text-slate-300" />
                        <span>Daily Attendance</span>
                    </a>
                @endcan

                @can('create-leave')
                    <a href="{{ route('leaves.create') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-btn bg-white/10 hover:bg-white/20 text-white border border-white/10 backdrop-blur-xs transition-all duration-150" wire:navigate>
                        <x-heroicon name="calendar" class="w-4 h-4 text-slate-300" />
                        <span>Request Leave</span>
                    </a>
                @endcan

                @can('view-payroll-run')
                    <a href="{{ route('payroll.runs.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold rounded-btn bg-white/10 hover:bg-white/20 text-white border border-white/10 backdrop-blur-xs transition-all duration-150" wire:navigate>
                        <x-heroicon name="banknotes" class="w-4 h-4 text-slate-300" />
                        <span>Payroll</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- KPI Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Total Employees --}}
        <x-stat-card
            label="Total Employees"
            :value="$totalEmployees"
            icon="users"
            color="indigo"
            :trend="$employeeTrend"
            trendLabel="vs last month ({{ $activeEmployees }} active)"
        />

        {{-- Present Today --}}
        <x-stat-card
            label="Present Today"
            :value="$todayPresent"
            icon="check-circle"
            color="green"
            :trend="$attendanceRate"
            trendLabel="rate · {{ $todayLate }} late today"
        />

        {{-- Absent Today --}}
        <x-stat-card
            label="Absent Today"
            :value="$todayAbsent"
            icon="x-circle"
            color="red"
            :trendLabel="($activeEmployees > 0 ? round(($todayAbsent / $activeEmployees) * 100) : 0) . '% of active workforce'"
        />

        {{-- On Leave / Pending --}}
        @if($isSuperAdmin)
            <x-stat-card
                label="On Leave / Pending"
                :value="$pendingLeaves"
                icon="calendar"
                color="amber"
                :trend="$leaveTrend"
                trendLabel="pending requests"
            />
        @else
            <x-stat-card
                label="Pending Leave Requests"
                :value="$pendingLeaves"
                icon="calendar"
                color="amber"
                :trendLabel="$pendingLeaves > 0 ? 'Awaiting review' : 'All requests up to date'"
            />
        @endif
    </div>

    {{-- Charts Row 1: Attendance 30-Day Trend & Leave Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Attendance Trend --}}
        <div class="lg:col-span-2">
            <x-chart-card title="30-Day Attendance Trend" subtitle="Daily breakdown of employee presence, late arrivals, and absences" height="300px">
                @if(empty($attendanceTrend['labels']) || count($attendanceTrend['labels']) === 0)
                    <x-empty-state
                        icon="chart-bar"
                        title="No attendance data yet"
                        description="No attendance records recorded for the past 30 days. Log daily attendance or import biometric data to view trends."
                        class="py-8"
                    >
                        <a href="{{ route('attendances.daily-register') }}" class="btn-primary mt-2" wire:navigate>
                            Go to Daily Attendance
                        </a>
                    </x-empty-state>
                @else
                    <canvas id="attendanceChart"></canvas>
                @endif
            </x-chart-card>
        </div>

        {{-- Leave Distribution --}}
        <div>
            <x-chart-card title="Leave Distribution" subtitle="Breakdown by leave type" height="300px">
                @if(empty($leaveDistribution['labels']) || count($leaveDistribution['labels']) === 0)
                    <x-empty-state
                        icon="calendar"
                        title="No leave records"
                        description="No leave applications recorded in the system yet."
                        class="py-8"
                    />
                @else
                    <canvas id="leaveChart"></canvas>
                @endif
            </x-chart-card>
        </div>
    </div>

    {{-- Charts Row 2: Headcount by Department & Workforce Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-chart-card title="Headcount by Department" subtitle="Active employees across departments" height="280px">
            @if(empty($headcountByDepartment['labels']) || count($headcountByDepartment['labels']) === 0)
                <x-empty-state
                    icon="building-office"
                    title="No department data"
                    description="Assign active employees to departments to view headcount distribution."
                    class="py-6"
                />
            @else
                <canvas id="departmentChart"></canvas>
            @endif
        </x-chart-card>

        <x-chart-card title="Workforce by Status" subtitle="Active, inactive, and other employment states" height="280px">
            @if(empty($headcountByStatus['labels']) || count($headcountByStatus['labels']) === 0)
                <x-empty-state
                    icon="users"
                    title="No employee records"
                    description="Add employees to view workforce status distribution."
                    class="py-6"
                />
            @else
                <canvas id="statusChart"></canvas>
            @endif
        </x-chart-card>
    </div>

    {{-- Bottom Operational Grid: Upcoming Events, Recent Employees, Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Upcoming Leaves & Holidays --}}
        <div class="card flex flex-col">
            <div class="card-header">
                <div>
                    <h3 class="text-section font-semibold text-gray-900">Upcoming Schedule</h3>
                    <p class="text-caption text-gray-500 mt-0.5">Approved leaves and public holidays</p>
                </div>
                <x-heroicon name="calendar" class="w-5 h-5 text-gray-400" />
            </div>
            <div class="card-body p-0 flex-1">
                @if($upcomingLeaves->isEmpty() && $upcomingHolidays->isEmpty())
                    <x-empty-state title="No upcoming events" description="No scheduled leaves or public holidays found." icon="calendar" class="py-10" />
                @else
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @foreach($upcomingHolidays as $holiday)
                            <div class="flex items-center gap-3.5 px-5 py-3.5 bg-indigo-50/30 hover:bg-indigo-50/60 transition-colors">
                                <div class="flex-shrink-0 w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex flex-col items-center justify-center font-bold leading-none">
                                    <span class="text-[10px] uppercase">{{ \Carbon\Carbon::parse($holiday->date)->format('M') }}</span>
                                    <span class="text-xs">{{ \Carbon\Carbon::parse($holiday->date)->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-caption font-semibold text-gray-900 truncate">{{ $holiday->name }}</p>
                                    <p class="text-micro text-indigo-600 font-medium">Public Holiday</p>
                                </div>
                                <span class="badge-info text-micro">{{ \Carbon\Carbon::parse($holiday->date)->diffForHumans() }}</span>
                            </div>
                        @endforeach

                        @foreach($upcomingLeaves as $leave)
                            <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-gray-50/60 transition-colors">
                                <div class="flex-shrink-0 w-9 h-9 rounded-full bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-caption border border-amber-200/60">
                                    {{ substr($leave->employee->first_name ?? 'E', 0, 1) }}{{ substr($leave->employee->last_name ?? '', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-caption font-medium text-gray-900 truncate">
                                        {{ $leave->employee->first_name ?? 'Employee' }} {{ $leave->employee->last_name ?? '' }}
                                    </p>
                                    <p class="text-micro text-gray-500 truncate">
                                        {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }} · {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d') }}
                                    </p>
                                </div>
                                <span class="badge-warning text-micro">{{ $leave->days }} day{{ $leave->days > 1 ? 's' : '' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Employees --}}
        <div class="card flex flex-col">
            <div class="card-header">
                <div>
                    <h3 class="text-section font-semibold text-gray-900">Recent Joinings</h3>
                    <p class="text-caption text-gray-500 mt-0.5">Newly onboarded team members</p>
                </div>
                <a href="{{ route('employees.index') }}" class="text-caption font-medium text-primary-600 hover:text-primary-700 transition-colors inline-flex items-center gap-1" wire:navigate>
                    View all <x-heroicon name="chevron-right" class="w-3.5 h-3.5" />
                </a>
            </div>
            <div class="card-body p-0 flex-1">
                @if($recentEmployees->isEmpty())
                    <x-empty-state title="No employees yet" description="Start by adding your first employee." icon="users" class="py-10" />
                @else
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @foreach($recentEmployees as $emp)
                            <a href="{{ route('employees.show', $emp) }}" wire:navigate class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-gray-50/60 transition-colors group">
                                <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center font-bold text-caption text-primary-700 border border-primary-200/60 group-hover:border-primary-300">
                                    {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name ?? '', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-caption font-semibold text-gray-900 truncate group-hover:text-primary-600 transition-colors">
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </p>
                                    <p class="text-micro text-gray-500 truncate">
                                        {{ $emp->designation->title ?? $emp->department->name ?? 'Staff' }}{{ $emp->branch->name ? ' · ' . $emp->branch->name : '' }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-micro text-gray-400">
                                    {{ $emp->hired_at?->diffForHumans() ?? $emp->created_at?->diffForHumans() }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity / Audit Log --}}
        <div class="card flex flex-col">
            <div class="card-header">
                <div>
                    <h3 class="text-section font-semibold text-gray-900">Recent HR Activity</h3>
                    <p class="text-caption text-gray-500 mt-0.5">Audit trail of system events</p>
                </div>
                <x-heroicon name="bell" class="w-5 h-5 text-gray-400" />
            </div>
            <div class="card-body p-0 flex-1">
                @if($recentActivity->isEmpty())
                    <x-empty-state title="No activity yet" description="System changes will be tracked automatically." icon="bell" class="py-10" />
                @else
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @foreach($recentActivity as $log)
                            @php
                                $eventColor = match($log->event) {
                                    'created' => 'bg-emerald-500',
                                    'updated' => 'bg-sky-500',
                                    'deleted' => 'bg-rose-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <div class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50/60 transition-colors">
                                <span class="w-2 h-2 rounded-full {{ $eventColor }} mt-1.5 shrink-0"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-caption text-gray-700 leading-snug">
                                        <span class="font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</span>
                                        {{ $log->event }}d a
                                        <span class="font-medium text-gray-900">{{ class_basename($log->auditable_type ?? 'record') }}</span>
                                    </p>
                                    <p class="text-micro text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const chartColors = {
                indigo: '#4f46e5',
                indigoLight: 'rgba(79, 70, 229, 0.12)',
                green: '#10b981',
                greenLight: 'rgba(16, 185, 129, 0.12)',
                amber: '#f59e0b',
                rose: '#f43f5e',
                roseLight: 'rgba(244, 63, 94, 0.1)',
                sky: '#0ea5e9',
                purple: '#8b5cf6',
                gray: '#94a3b8'
            };

            const palette = [chartColors.indigo, chartColors.green, chartColors.amber, chartColors.sky, chartColors.purple, chartColors.rose];

            function initDashboardCharts() {
                if (typeof Chart === 'undefined') return;

                Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
                Chart.defaults.font.size = 12;
                Chart.defaults.color = '#64748b';

                // 1. Attendance 30-Day Trend Chart
                const attCanvas = document.getElementById('attendanceChart');
                if (attCanvas) {
                    const existingAtt = Chart.getChart(attCanvas);
                    if (existingAtt) existingAtt.destroy();

                    const attData = @json($attendanceTrend);
                    if (attData.labels && attData.labels.length > 0) {
                        new Chart(attCanvas, {
                            type: 'line',
                            data: {
                                labels: attData.labels,
                                datasets: [
                                    {
                                        label: 'Present',
                                        data: attData.present || [],
                                        borderColor: chartColors.indigo,
                                        backgroundColor: chartColors.indigoLight,
                                        fill: true,
                                        tension: 0.35,
                                        borderWidth: 2,
                                        pointRadius: 2,
                                        pointHoverRadius: 5,
                                    },
                                    {
                                        label: 'Late',
                                        data: attData.late || [],
                                        borderColor: chartColors.amber,
                                        backgroundColor: 'transparent',
                                        borderDash: [4, 4],
                                        tension: 0.35,
                                        borderWidth: 1.5,
                                        pointRadius: 0,
                                        pointHoverRadius: 4,
                                    },
                                    {
                                        label: 'Absent',
                                        data: attData.absent || [],
                                        borderColor: chartColors.rose,
                                        backgroundColor: 'transparent',
                                        tension: 0.35,
                                        borderWidth: 1.5,
                                        pointRadius: 0,
                                        pointHoverRadius: 4,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        align: 'end',
                                        labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { maxTicksLimit: 8, font: { size: 11 } }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: '#f1f5f9' },
                                        ticks: { font: { size: 11 }, precision: 0 }
                                    }
                                }
                            }
                        });
                    }
                }

                // 2. Leave Distribution Chart
                const leaveCanvas = document.getElementById('leaveChart');
                if (leaveCanvas) {
                    const existingLeave = Chart.getChart(leaveCanvas);
                    if (existingLeave) existingLeave.destroy();

                    const leaveData = @json($leaveDistribution);
                    if (leaveData.labels && leaveData.labels.length > 0 && leaveData.data && leaveData.data.length > 0) {
                        new Chart(leaveCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: leaveData.labels,
                                datasets: [{
                                    data: leaveData.data,
                                    backgroundColor: palette.slice(0, leaveData.labels.length),
                                    borderWidth: 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { padding: 12, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                                    }
                                },
                                cutout: '70%',
                            }
                        });
                    }
                }

                // 3. Department Headcount Chart
                const deptCanvas = document.getElementById('departmentChart');
                if (deptCanvas) {
                    const existingDept = Chart.getChart(deptCanvas);
                    if (existingDept) existingDept.destroy();

                    const deptData = @json($headcountByDepartment);
                    if (deptData.labels && deptData.labels.length > 0 && deptData.data && deptData.data.length > 0) {
                        new Chart(deptCanvas, {
                            type: 'bar',
                            data: {
                                labels: deptData.labels,
                                datasets: [{
                                    label: 'Headcount',
                                    data: deptData.data,
                                    backgroundColor: chartColors.indigo,
                                    borderRadius: 6,
                                    maxBarThickness: 32,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: { font: { size: 11 } }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: '#f1f5f9' },
                                        ticks: { precision: 0, font: { size: 11 } }
                                    }
                                }
                            }
                        });
                    }
                }

                // 4. Workforce by Status Chart
                const statusCanvas = document.getElementById('statusChart');
                if (statusCanvas) {
                    const existingStatus = Chart.getChart(statusCanvas);
                    if (existingStatus) existingStatus.destroy();

                    const statusData = @json($headcountByStatus);
                    if (statusData.labels && statusData.labels.length > 0 && statusData.data && statusData.data.length > 0) {
                        const statusColorsMap = {
                            active: chartColors.green,
                            inactive: '#94a3b8',
                            terminated: chartColors.rose,
                            suspended: chartColors.amber,
                        };
                        new Chart(statusCanvas, {
                            type: 'doughnut',
                            data: {
                                labels: statusData.labels,
                                datasets: [{
                                    data: statusData.data,
                                    backgroundColor: statusData.labels.map((l, i) => statusColorsMap[l.toLowerCase()] || palette[i % palette.length]),
                                    borderWidth: 2,
                                    borderColor: '#ffffff',
                                    hoverOffset: 4,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { padding: 12, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
                                    }
                                },
                                cutout: '70%',
                            }
                        });
                    }
                }
            }

            if (document.readyState !== 'loading') {
                initDashboardCharts();
            } else {
                document.addEventListener('DOMContentLoaded', initDashboardCharts);
            }
            document.addEventListener('livewire:navigated', initDashboardCharts);
        })();
    </script>
    @endpush
</x-app-layout>

