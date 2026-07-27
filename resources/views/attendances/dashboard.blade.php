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
                <x-chart-card title="Attendance Trend (30 Days)" height="280px">
                    <canvas id="attendanceTrendChart"></canvas>
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
                    <div class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        <p class="text-sm text-gray-500">No imports yet.</p>
                        <a href="{{ route('attendances.import.create') }}" class="mt-2 text-sm font-medium text-primary-600 hover:text-primary-700" wire:navigate>Import attendance →</a>
                    </div>
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
                                <span class="badge-{{ $import->status === 'completed' ? 'success' : ($import->status === 'preview' ? 'warning' : 'default') }}">{{ ucfirst($import->status) }}</span>
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
                    <div class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>
                        <p class="text-sm text-gray-500">No attendance records for today.</p>
                    </div>
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
                                <span class="badge-{{ $record->status === 'present' ? 'success' : ($record->status === 'late' ? 'warning' : ($record->status === 'half-day' ? 'info' : 'danger')) }}">{{ ucfirst($record->status) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trendData = @json($attendanceTrend);
            new Chart(document.getElementById('attendanceTrendChart'), {
                type: 'line',
                data: {
                    labels: trendData.labels,
                    datasets: [{
                        label: 'Present',
                        data: trendData.present,
                        borderColor: 'rgba(99, 102, 241, 0.8)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true, tension: 0.4, borderWidth: 2, pointRadius: 0, pointHoverRadius: 4,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 7 } },
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
