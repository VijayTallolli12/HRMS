<x-app-layout>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Attendance Dashboard</h2>
                <p class="mt-1 text-sm text-gray-500">Real-time attendance overview for {{ $isSuperAdmin ? 'all branches' : 'your branch' }}.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('attendances.import.create') }}" class="btn-primary" wire:navigate>
                    <x-heroicon name="arrow-up-tray" class="w-4 h-4" />
                    Import Attendance
                </a>
                <a href="{{ route('attendances.daily-register') }}" class="btn-secondary" wire:navigate>
                    <x-heroicon name="clipboard-document-list" class="w-4 h-4" />
                    Daily Register
                </a>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <x-stat-card label="Total Employees" :value="$totalEmployees" icon="users" color="indigo" />
        <x-stat-card label="Present Today" :value="$presentToday" icon="check-circle" color="green" :trend="$attendanceRate" trendLabel="rate" />
        <x-stat-card label="Late Today" :value="$lateToday" icon="clock" color="amber" />
        <x-stat-card label="Absent Today" :value="$absentToday" icon="x-circle" color="red" />
        <x-stat-card label="Missing Punches" :value="$missingPunchesToday" icon="exclamation-triangle" color="purple" />
    </div>

    {{-- Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="lg:col-span-2">
            <x-chart-card title="Attendance Trend (30 Days)" height="280px">
                <canvas id="attendanceTrendChart"></canvas>
            </x-chart-card>
        </div>

        {{-- Quick Stats --}}
        <div class="card p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Today's Summary</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Attendance Rate</span>
                    <span class="text-sm font-bold text-gray-900">{{ $attendanceRate }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $attendanceRate }}%"></div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm text-gray-500">Total Hours Today</span>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($todayEmployees->sum(fn($e) => $e->hours_worked ?? 0), 1) }}h</span>
                </div>

                @if($missingPunchesToday > 0)
                <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 mt-3">
                    <div class="flex items-center gap-2">
                        <x-heroicon name="exclamation-triangle" class="w-4 h-4 text-amber-600" />
                        <span class="text-sm font-medium text-amber-800">{{ $missingPunchesToday }} missing punch{{ $missingPunchesToday > 1 ? 'es' : '' }} need attention</span>
                    </div>
                    <a href="{{ route('missing-punches.index') }}" class="text-xs text-amber-600 underline mt-1 inline-block" wire:navigate>View all →</a>
                </div>
                @endif

                <div class="pt-3 border-t border-gray-100">
                    <a href="{{ route('attendances.reports.monthly') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>View Monthly Register →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Imports + Recent Records --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Recent Imports</h3>
                <a href="{{ route('attendances.import.history') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>View all</a>
            </div>
            @if($recentImports->isEmpty())
                <div class="px-6 py-12 text-center">
                    <x-heroicon name="arrow-up-tray" class="w-10 h-10 text-gray-300 mx-auto mb-3" />
                    <p class="text-sm text-gray-500">No imports yet.</p>
                    <a href="{{ route('attendances.import.create') }}" class="mt-2 text-sm font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>Import attendance →</a>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($recentImports as $import)
                        <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 h-9 w-9 rounded-lg {{ $import->status === 'completed' ? 'bg-emerald-100' : ($import->status === 'preview' ? 'bg-amber-100' : 'bg-gray-100') }} flex items-center justify-center">
                                <x-heroicon name="arrow-up-tray" class="w-4 h-4 {{ $import->status === 'completed' ? 'text-emerald-600' : ($import->status === 'preview' ? 'text-amber-600' : 'text-gray-500') }}" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $import->filename }}</p>
                                <p class="text-xs text-gray-500">{{ $import->valid_rows }}/{{ $import->total_rows }} rows · {{ $import->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $import->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($import->status === 'preview' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($import->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Today's Records</h3>
                <a href="{{ route('attendances.daily-register', ['date' => now()->format('Y-m-d')]) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500" wire:navigate>View register</a>
            </div>
            @if($todayEmployees->isEmpty())
                <div class="px-6 py-12 text-center">
                    <x-heroicon name="clipboard-document-list" class="w-10 h-10 text-gray-300 mx-auto mb-3" />
                    <p class="text-sm text-gray-500">No attendance records for today.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @foreach($todayEmployees->take(10) as $record)
                        <div class="px-6 py-3 flex items-center gap-3">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-xs font-bold text-indigo-600">{{ substr($record->employee->first_name ?? '?', 0, 1) }}{{ substr($record->employee->last_name ?? '', 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $record->employee->first_name ?? '' }} {{ $record->employee->last_name ?? '' }}</p>
                                <p class="text-xs text-gray-500">{{ $record->clock_in ?? '--:--' }} → {{ $record->clock_out ?? '--:--' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ $record->status === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $record->status === 'late' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $record->status === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}
                                {{ $record->status === 'half-day' ? 'bg-sky-100 text-sky-700' : '' }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
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
