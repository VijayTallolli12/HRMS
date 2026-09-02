<x-app-layout>
    <x-page-header title="Attendance Dashboard" description="Real-time attendance overview for {{ $isSuperAdmin ? 'all branches' : 'your branch' }}." icon="chart-bar">
        <x-slot name="breadcrumb">
            <a href="{{ route('dashboard') }}" wire:navigate>Home</a>
            <span class="breadcrumb-separator">/</span>
            <span>Workforce</span>
            <span class="breadcrumb-separator">/</span>
            <span>Attendance Dashboard</span>
        </x-slot>
        <x-slot name="actions">
            <a href="{{ route('attendances.import.create') }}" class="btn-primary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Import Attendance
            </a>
            <a href="{{ route('attendances.daily-register') }}" class="btn-secondary" wire:navigate>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>
                Daily Register
            </a>
        </x-slot>
    </x-page-header>

    <div class="space-y-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <x-stat-card label="Total Employees" :value="$totalEmployees" icon="users" color="indigo" />
            <x-stat-card label="Present Today" :value="$presentToday" icon="check-circle" color="green" :trend="$attendanceRate" trendLabel="rate" />
            <x-stat-card label="Late Today" :value="$lateToday" icon="clock" color="amber" />
            <x-stat-card label="Absent Today" :value="$absentToday" icon="x-circle" color="red" />
            <x-stat-card label="Missing Punches" :value="$missingPunchesToday" icon="exclamation-triangle" color="purple" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2">
                <x-chart-card title="Attendance Trend (30 Days)" subtitle="Daily presence, late arrivals, and absences" height="300px">
                    @if(empty($attendanceTrend['labels']) || count($attendanceTrend['labels']) === 0)
                        <x-empty-state
                            icon="chart-bar"
                            title="No attendance data yet"
                            description="No attendance records recorded for the past 30 days. Log daily attendance or import biometric records to view workforce trends."
                            class="py-8"
                        >
                            <div class="flex items-center justify-center gap-3 mt-2">
                                <a href="{{ route('attendances.daily-register') }}" class="btn-primary" wire:navigate>
                                    Go to Daily Attendance
                                </a>
                                <a href="{{ route('attendances.import.create') }}" class="btn-secondary" wire:navigate>
                                    Import Attendance
                                </a>
                            </div>
                        </x-empty-state>
                    @else
                        <canvas id="attendanceTrendChart"></canvas>
                    @endif
                </x-chart-card>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-sm font-semibold text-gray-900">Today's Summary</h3>
                </div>
                <div class="card-body space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-body text-gray-500">Attendance Rate</span>
                        <span class="text-body font-bold text-gray-900">{{ $attendanceRate }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $attendanceRate }}%"></div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-body text-gray-500">Total Hours Today</span>
                        <span class="text-body font-bold text-gray-900">{{ number_format($todayEmployees->sum(fn($e) => $e->hours_worked ?? 0), 1) }}h</span>
                    </div>

                    @if($missingPunchesToday > 0)
                        <div class="p-3 bg-amber-50 rounded-card border border-amber-200 mt-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                <span class="text-sm font-medium text-amber-800">{{ $missingPunchesToday }} missing punch{{ $missingPunchesToday > 1 ? 'es' : '' }} need attention</span>
                            </div>
                            <a href="{{ route('missing-punches.index') }}" class="text-xs text-amber-600 underline mt-1 inline-block" wire:navigate>View all →</a>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-gray-100">
                        <a href="{{ route('attendances.reports.monthly') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700" wire:navigate>View Monthly Register →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Recent Imports</h3>
                    <a href="{{ route('attendances.import.history') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700" wire:navigate>View all</a>
                </div>
                @if($recentImports->isEmpty())
                    <x-empty-state
                        icon="document-arrow-up"
                        title="No imports yet"
                        description="Import biometric or CSV attendance records to get started."
                        class="py-8"
                    >
                        <a href="{{ route('attendances.import.create') }}" class="btn-secondary mt-2" wire:navigate>Import attendance →</a>
                    </x-empty-state>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentImports as $import)
                            <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0 h-9 w-9 rounded-card {{ $import->status === 'completed' ? 'bg-emerald-50' : ($import->status === 'preview' ? 'bg-amber-50' : 'bg-gray-100') }} flex items-center justify-center">
                                    <svg class="w-4 h-4 {{ $import->status === 'completed' ? 'text-emerald-600' : ($import->status === 'preview' ? 'text-amber-600' : 'text-gray-500') }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $import->filename }}</p>
                                    <p class="text-xs text-gray-500">{{ $import->valid_rows }}/{{ $import->total_rows }} rows · {{ $import->created_at->diffForHumans() }}</p>
                                </div>
                                <x-status-badge :status="$import->status" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Today's Records</h3>
                    <a href="{{ route('attendances.daily-register', ['date' => now()->format('Y-m-d')]) }}" class="text-xs font-medium text-primary-600 hover:text-primary-700" wire:navigate>View register</a>
                </div>
                @if($todayEmployees->isEmpty())
                    <x-empty-state
                        icon="calendar"
                        title="No records today"
                        description="No attendance records recorded for today yet."
                        class="py-8"
                    >
                        <a href="{{ route('attendances.daily-register') }}" class="btn-secondary mt-2" wire:navigate>Open Daily Register →</a>
                    </x-empty-state>
                @else
                    <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                        @foreach($todayEmployees->take(10) as $record)
                            <div class="px-6 py-3 flex items-center gap-3">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                    <span class="text-xs font-bold text-primary-600">{{ substr($record->employee->first_name ?? '?', 0, 1) }}{{ substr($record->employee->last_name ?? '', 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $record->employee->first_name ?? '' }} {{ $record->employee->last_name ?? '' }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->clock_in ?? '--:--' }} → {{ $record->clock_out ?? '--:--' }}</p>
                                </div>
                                <x-status-badge :status="$record->status" />
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
            function initAttendanceTrend() {
                const canvas = document.getElementById('attendanceTrendChart');
                if (!canvas) return;

                const existing = Chart.getChart(canvas);
                if (existing) {
                    existing.destroy();
                }

                const trendData = @json($attendanceTrend);
                if (!trendData.labels || trendData.labels.length === 0) return;

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: trendData.labels,
                        datasets: [
                            {
                                label: 'Present',
                                data: trendData.present || [],
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.12)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 2,
                                pointRadius: 2,
                                pointHoverRadius: 5,
                            },
                            {
                                label: 'Late',
                                data: trendData.late || [],
                                borderColor: '#f59e0b',
                                backgroundColor: 'transparent',
                                borderDash: [4, 4],
                                tension: 0.35,
                                borderWidth: 1.5,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                            },
                            {
                                label: 'Absent',
                                data: trendData.absent || [],
                                borderColor: '#f43f5e',
                                backgroundColor: 'rgba(244, 63, 94, 0.08)',
                                fill: false,
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
                                labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true, font: { size: 11 } }
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
                                ticks: { precision: 0, font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            if (document.readyState !== 'loading') {
                initAttendanceTrend();
            } else {
                document.addEventListener('DOMContentLoaded', initAttendanceTrend);
            }
            document.addEventListener('livewire:navigated', initAttendanceTrend);
        })();
    </script>
    @endpush
</x-app-layout>
