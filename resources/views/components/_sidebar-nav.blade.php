@php
    $user = auth()->user();
    $isSuperAdmin = $user?->isSuperAdmin();
    $isBranchAdmin = $user?->isBranchAdmin();
    $currentRoute = request()->route()->getName();
@endphp

{{-- Logo --}}
<div class="flex h-16 items-center gap-3">
    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-indigo-600">
        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
        </svg>
    </div>
    <div>
        <span class="text-lg font-bold text-white tracking-tight">{{ config('app.name', 'HRMS') }}</span>
        <span class="block text-[10px] text-slate-400 -mt-0.5">Human Resource Management</span>
    </div>
</div>

{{-- Navigation --}}
<nav class="flex flex-1 flex-col mt-2">
    <ul role="list" class="flex flex-1 flex-col gap-y-1">
        {{-- Dashboard --}}
        <li>
            <a href="{{ route('dashboard') }}" wire:navigate
               class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-heroicon name="home" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'dashboard' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                Dashboard
            </a>
        </li>

        {{-- People Section --}}
        <li class="mt-6">
            <h3 class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">People</h3>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    <a href="{{ route('employees.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'employee') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="users" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'employee') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Employees
                    </a>
                </li>
                @can('view-department')
                <li>
                    <a href="{{ route('departments.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'departments.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="building-office-2" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'departments.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Departments
                    </a>
                </li>
                @endcan
                @can('view-designation')
                <li>
                    <a href="{{ route('designations.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'designations.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="academic-cap" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'designations.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Designations
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        {{-- Workforce Section --}}
        <li class="mt-6">
            <h3 class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Workforce</h3>
            <ul role="list" class="mt-2 space-y-1">
                @php
                    $attRoutes = ['attendances.dashboard', 'attendances.import', 'attendances.import', 'attendances.daily-register', 'attendances.reports', 'attendances.corrections', 'missing-punches'];
                    $isAttendance = str_starts_with($currentRoute, 'attendance') || str_starts_with($currentRoute, 'missing-punch');
                @endphp
                <li>
                    <a href="{{ route('attendances.dashboard') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'attendances.dashboard' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="chart-bar" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'attendances.dashboard' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Attendance Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.import.create') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'attendances.import') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="arrow-up-tray" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.import') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Import Attendance
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.daily-register') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'attendances.daily-register' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="clipboard-document-list" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'attendances.daily-register' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Daily Register
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.reports.monthly') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'attendances.reports.monthly' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="calendar-days" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'attendances.reports.monthly' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Monthly Register
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.corrections.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'attendances.corrections') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="pencil-square" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.corrections') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Corrections
                    </a>
                </li>
                <li>
                    <a href="{{ route('missing-punches.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'missing-punches') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="exclamation-triangle" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'missing-punches') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Missing Punches
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.reports') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'attendances.reports' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="document-chart-bar" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'attendances.reports' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Reports
                    </a>
                </li>

                <li class="pt-2 border-t border-slate-700/50 mt-2">
                    <a href="{{ route('attendances.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'attendances.index') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="clock" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.index') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        All Attendance Records
                    </a>
                </li>
                <li>
                    <a href="{{ route('leaves.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'leave') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="calendar" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'leave') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Leave
                    </a>
                </li>
                @can('view-shift')
                <li>
                    <a href="{{ route('shifts.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'shifts.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="calendar-days" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'shifts.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Shifts
                    </a>
                </li>
                @endcan
                @can('view-holiday')
                <li>
                    <a href="{{ route('holidays.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'holidays.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="calendar" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'holidays.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Holidays
                    </a>
                </li>
                @endcan
            </ul>
        </li>

        {{-- Organization Section (Super Admin only) --}}
        @if($isSuperAdmin)
        <li class="mt-6">
            <h3 class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Organization</h3>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    <a href="{{ route('organizations.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'organizations.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="building-office" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'organizations.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Organizations
                    </a>
                </li>
                <li>
                    <a href="{{ route('branches.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'branches.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="building-office-2" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'branches.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Branches
                    </a>
                </li>
            </ul>
        </li>
        @endif

        {{-- Payroll Section --}}
        <li class="mt-6">
            <h3 class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Payroll</h3>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    <a href="{{ route('payroll.runs.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'payroll') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="currency-dollar" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'payroll') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Payroll Runs
                    </a>
                </li>
                @if($isSuperAdmin)
                <li>
                    <a href="{{ route('payroll.salary-components.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'payroll.salary-components.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="wrench-screwdriver" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'payroll.salary-components.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Salary Components
                    </a>
                </li>
                <li>
                    <a href="{{ route('payroll.salary-structures.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $currentRoute === 'payroll.salary-structures.index' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="document-text" class="h-5 w-5 flex-shrink-0 {{ $currentRoute === 'payroll.salary-structures.index' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Salary Structures
                    </a>
                </li>
                @endif
            </ul>
        </li>

        {{-- Admin Section (Super Admin only) --}}
        @if($isSuperAdmin)
        <li class="mt-6">
            <h3 class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Administration</h3>
            <ul role="list" class="mt-2 space-y-1">
                <li>
                    <a href="{{ route('user-management.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'user-management') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="key" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'user-management') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        User Management
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.index') }}" wire:navigate
                       class="group flex items-center gap-x-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'settings') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <x-heroicon name="cog" class="h-5 w-5 flex-shrink-0 {{ str_starts_with($currentRoute, 'settings') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                        Settings
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</nav>

{{-- User info at bottom --}}
<div class="border-t border-slate-700 pt-4 mt-4">
    <div class="flex items-center gap-x-3">
        <div class="flex items-center justify-center h-9 w-9 rounded-full bg-slate-700">
            <span class="text-sm font-bold text-white">{{ substr($user->name ?? 'U', 0, 1) }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-white truncate">{{ $user->name ?? '' }}</p>
            <p class="text-xs text-slate-400 truncate">{{ $user->email ?? '' }}</p>
        </div>
    </div>
</div>
