@php
    use Illuminate\Support\Facades\Route;

    $user = auth()->user();
    $currentRoute = request()->route()?->getName() ?? '';
    $can = fn (?string $permission = null) => $permission === null || $user?->can($permission);
    $href = fn (string $route, array $parameters = []) => Route::has($route) ? route($route, $parameters) : null;
    $matches = fn (array $patterns) => collect($patterns)->contains(fn ($pattern) => str($currentRoute)->is($pattern));

    $navSections = [
        [
            'key' => 'attendance',
            'label' => 'Attendance',
            'icon' => 'calendar-days',
            'active' => $matches(['attendances*', 'attendance-adjustments*', 'missing-punches*', 'shifts*', 'shift-assignments*']),
            'items' => [
                ['label' => 'Overview', 'route' => 'attendances.dashboard', 'permission' => 'view-attendance', 'patterns' => ['attendances.dashboard']],
                ['label' => 'Daily Attendance', 'route' => 'attendances.daily-register', 'permission' => 'view-attendance', 'patterns' => ['attendances.daily-register', 'attendances.index']],
                ['label' => 'Shift Management', 'route' => 'shifts.index', 'permission' => 'view-shift', 'patterns' => ['shifts*', 'shift-assignments*']],
                [
                    'label' => 'Regularisation',
                    'children' => [
                        ['label' => 'Attendance Corrections', 'route' => 'attendances.corrections.index', 'permission' => 'view-attendance-adjustment', 'patterns' => ['attendances.corrections*', 'attendance-adjustments*']],
                        ['label' => 'Missing Punches', 'route' => 'missing-punches.index', 'permission' => 'view-attendance', 'patterns' => ['missing-punches*']],
                    ],
                ],
            ],
        ],
        [
            'key' => 'leaves',
            'label' => 'Leaves',
            'icon' => 'calendar',
            'active' => $matches(['leaves*', 'leave-balances*', 'leave-types*', 'holidays*']),
            'items' => [
                ['label' => 'Overview', 'route' => 'leaves.index', 'permission' => 'view-leave', 'patterns' => ['leaves.index']],
                ['label' => 'Apply Leave', 'route' => 'leaves.create', 'permission' => 'create-leave', 'patterns' => ['leaves.create']],
                ['label' => 'Leave Requests', 'route' => 'leaves.index', 'permission' => 'view-leave', 'patterns' => ['leaves.show', 'leaves.edit']],
                ['label' => 'Leave Balance', 'route' => 'leave-balances.index', 'permission' => 'view-leave-balance', 'patterns' => ['leave-balances*']],
                ['label' => 'Holiday Calendar', 'route' => 'holidays.index', 'permission' => 'view-holiday', 'patterns' => ['holidays*']],
            ],
        ],
        [
            'key' => 'payroll',
            'label' => 'Payroll',
            'icon' => 'banknotes',
            'active' => $matches(['payroll*']),
            'items' => [
                ['label' => 'Overview', 'route' => 'payroll.runs.index', 'permission' => 'view-payroll-run', 'patterns' => ['payroll.runs.index']],
                ['label' => 'Salary Processing', 'route' => 'payroll.runs.index', 'permission' => 'view-payroll-run', 'patterns' => ['payroll.runs*']],
                ['label' => 'Salary Structure', 'route' => 'payroll.salary-structures.index', 'permission' => 'view-salary-structure', 'patterns' => ['payroll.salary-structures*']],
                ['label' => 'Payslips', 'route' => 'payroll.runs.index', 'permission' => 'view-payslip', 'patterns' => ['payroll.payslips*']],
                ['label' => 'Bonuses & Incentives', 'route' => 'payroll.salary-components.index', 'permission' => 'view-salary-component', 'query' => ['type' => 'earning'], 'patterns' => ['payroll.salary-components*']],
                ['label' => 'Deductions', 'route' => 'payroll.salary-components.index', 'permission' => 'view-salary-component', 'query' => ['type' => 'deduction'], 'patterns' => ['payroll.salary-components*']],
                ['label' => 'Reimbursements', 'route' => 'payroll.salary-components.index', 'permission' => 'view-salary-component', 'query' => ['type' => 'benefit'], 'patterns' => ['payroll.salary-components*']],
            ],
        ],
        [
            'key' => 'employees',
            'label' => 'Employees',
            'icon' => 'users',
            'active' => $matches(['employees*', 'departments*', 'designations*']),
            'items' => [
                ['label' => 'Employee Directory', 'route' => 'employees.index', 'permission' => 'view-employee', 'patterns' => ['employees*']],
                ['label' => 'Departments', 'route' => 'departments.index', 'permission' => 'view-department', 'patterns' => ['departments*']],
                ['label' => 'Designations', 'route' => 'designations.index', 'permission' => 'view-designation', 'patterns' => ['designations*']],
            ],
        ],
        [
            'key' => 'settings',
            'label' => 'Settings',
            'icon' => 'cog',
            'active' => $matches(['settings*', 'shifts*', 'late-policies*', 'weekend-policies*', 'leave-types*', 'payroll.salary-components*', 'user-management*', 'reporting-hierarchies*']),
            'items' => [
                ['label' => 'Company Settings', 'route' => 'settings.index', 'permission' => 'view-settings', 'patterns' => ['settings*']],
                ['label' => 'Shift Settings', 'route' => 'shifts.index', 'permission' => 'view-shift', 'patterns' => ['shifts*']],
                ['label' => 'Attendance Rules', 'route' => 'late-policies.index', 'permission' => 'view-late-policy', 'patterns' => ['late-policies*']],
                ['label' => 'Leave Policies', 'route' => 'weekend-policies.index', 'permission' => 'view-weekend-policy', 'patterns' => ['weekend-policies*']],
                ['label' => 'Leave Types', 'route' => 'leave-types.index', 'permission' => 'view-leave-type', 'patterns' => ['leave-types*']],
                ['label' => 'Payroll Settings', 'route' => 'payroll.salary-structures.index', 'permission' => 'view-salary-structure', 'patterns' => ['payroll.salary-structures*']],
                ['label' => 'Salary Components', 'route' => 'payroll.salary-components.index', 'permission' => 'view-salary-component', 'patterns' => ['payroll.salary-components*']],
                ['label' => 'Approval Workflow', 'route' => 'reporting-hierarchies.index', 'permission' => 'view-reporting-hierarchy', 'patterns' => ['reporting-hierarchies*']],
                ['label' => 'Roles & Permissions', 'route' => 'user-management.index', 'permission' => 'view-user-management', 'patterns' => ['user-management*']],
            ],
        ],
    ];

    $visibleItems = function (array $items) use ($can, $href) {
        return collect($items)->map(function ($item) use ($can, $href) {
            if (isset($item['children'])) {
                $item['children'] = collect($item['children'])
                    ->filter(fn ($child) => $can($child['permission'] ?? null) && $href($child['route']))
                    ->values()
                    ->all();

                return count($item['children']) ? $item : null;
            }

            return $can($item['permission'] ?? null) && $href($item['route']) ? $item : null;
        })->filter()->values()->all();
    };
@endphp

<div class="px-5 py-5">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
        @if($branding['logo_url'])
            <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['company_name'] }}" class="w-10 h-10 rounded-[10px] object-contain bg-white p-1 shadow-md shadow-primary-500/20 transition-shadow duration-200 group-hover:shadow-lg group-hover:shadow-primary-500/30">
        @else
            <div class="flex items-center justify-center w-10 h-10 rounded-[10px] bg-gradient-to-br from-primary-500 to-primary-700 shadow-md shadow-primary-500/20 transition-shadow duration-200 group-hover:shadow-lg group-hover:shadow-primary-500/30">
                <x-heroicon name="building-office" class="w-5 h-5 text-white" />
            </div>
        @endif
        <div class="min-w-0">
            <span class="block truncate text-[15px] font-bold text-white">{{ $branding['company_name'] }}</span>
            <span class="block truncate text-[10px] font-medium uppercase tracking-[0.1em] text-slate-500">HRMS</span>
        </div>
    </a>
</div>

<nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-5" aria-label="Sidebar">
    <a href="{{ route('dashboard') }}" wire:navigate
       class="group flex min-h-10 items-center gap-3 rounded-btn px-3 py-2 text-[13px] font-medium transition-all duration-150 {{ $currentRoute === 'dashboard' ? 'bg-primary-600/10 text-primary-300' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
        <x-heroicon name="home" class="w-5 h-5 {{ $currentRoute === 'dashboard' ? 'text-primary-300' : 'text-slate-500 group-hover:text-slate-300' }}" />
        <span>Dashboard</span>
    </a>

    @foreach($navSections as $section)
        @php
            $items = $visibleItems($section['items']);
        @endphp

        @if(count($items))
            <div
                x-data="{ open: {{ $section['active'] ? 'true' : 'false' }} }"
                class="pt-1"
            >
                <button
                    type="button"
                    x-on:click="open = !open"
                    x-on:keydown.enter.prevent="open = !open"
                    x-on:keydown.space.prevent="open = !open"
                    x-bind:aria-expanded="open.toString()"
                    class="group flex min-h-10 w-full items-center gap-3 rounded-btn px-3 py-2 text-left text-[13px] font-semibold transition-all duration-150 {{ $section['active'] ? 'bg-white/5 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                    aria-controls="sidebar-section-{{ $section['key'] }}"
                >
                    <x-heroicon :name="$section['icon']" class="w-5 h-5 {{ $section['active'] ? 'text-primary-300' : 'text-slate-500 group-hover:text-slate-300' }}" />
                    <span class="flex-1">{{ $section['label'] }}</span>
                    <x-heroicon name="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200" x-bind:class="{ 'rotate-180 text-slate-300': open }" />
                </button>

                <div
                    id="sidebar-section-{{ $section['key'] }}"
                    x-show="open"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="mt-1 space-y-1 pl-4"
                >
                    @foreach($items as $item)
                        @if(isset($item['children']))
                            @php
                                $childrenActive = collect($item['children'])->contains(fn ($child) => $matches($child['patterns']));
                            @endphp
                            <div x-data="{ open: {{ $childrenActive ? 'true' : 'false' }} }">
                                <button
                                    type="button"
                                    x-on:click="open = !open"
                                    x-on:keydown.enter.prevent="open = !open"
                                    x-on:keydown.space.prevent="open = !open"
                                    x-bind:aria-expanded="open.toString()"
                                    class="group flex min-h-9 w-full items-center gap-2 rounded-btn px-3 py-2 text-left text-[12px] font-medium transition-colors duration-150 {{ $childrenActive ? 'text-primary-300' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full {{ $childrenActive ? 'bg-primary-300' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <x-heroicon name="chevron-down" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" x-bind:class="{ 'rotate-180 text-slate-300': open }" />
                                </button>

                                <div x-show="open" x-cloak class="mt-1 space-y-1 pl-4">
                                    @foreach($item['children'] as $child)
                                        @php
                                            $isActive = $matches($child['patterns']);
                                            $url = $href($child['route'], $child['query'] ?? []);
                                        @endphp
                                        <a href="{{ $url }}" wire:navigate
                                           class="group flex min-h-8 items-center gap-2 rounded-btn px-3 py-1.5 text-[12px] font-medium transition-colors duration-150 {{ $isActive ? 'bg-primary-600/10 text-primary-300' : 'text-slate-500 hover:bg-white/5 hover:text-slate-200' }}">
                                            <span class="h-1 w-1 rounded-full {{ $isActive ? 'bg-primary-300' : 'bg-slate-700 group-hover:bg-slate-400' }}"></span>
                                            <span>{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $isActive = $matches($item['patterns']);
                                $url = $href($item['route'], $item['query'] ?? []);
                            @endphp
                            <a href="{{ $url }}" wire:navigate
                               class="group flex min-h-9 items-center gap-2 rounded-btn px-3 py-2 text-[12px] font-medium transition-colors duration-150 {{ $isActive ? 'bg-primary-600/10 text-primary-300' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isActive ? 'bg-primary-300' : 'bg-slate-600 group-hover:bg-slate-400' }}"></span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</nav>
