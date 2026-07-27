@php
    $user = auth()->user();
    $isSuperAdmin = $user?->isSuperAdmin();
    $isBranchAdmin = $user?->isBranchAdmin();
    $currentRoute = request()->route()->getName();
@endphp

{{-- Logo --}}
<div class="px-5 pt-6 pb-4">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
        @if($branding['logo_url'])
            <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['company_name'] }}" class="w-9 h-9 rounded-[10px] object-contain bg-white p-1 shadow-md shadow-primary-500/20 group-hover:shadow-lg group-hover:shadow-primary-500/30 transition-shadow duration-200">
        @else
            <div class="flex items-center justify-center w-9 h-9 rounded-[10px] bg-gradient-to-br from-primary-500 to-primary-700 shadow-md shadow-primary-500/20 group-hover:shadow-lg group-hover:shadow-primary-500/30 transition-shadow duration-200">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                </svg>
            </div>
        @endif
        <div>
            <span class="text-[15px] font-bold text-white tracking-tight">{{ $branding['company_name'] }}</span>
            <span class="block text-[10px] text-slate-400 -mt-0.5 font-medium tracking-wide">Enterprise HRMS</span>
        </div>
    </a>
</div>

{{-- Navigation --}}
<nav class="flex-1 px-3 pb-4 space-y-1 overflow-y-auto">
    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'dashboard' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'dashboard' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        Dashboard
    </a>

    {{-- ── People ── --}}
    <div class="pt-5 pb-1.5 px-3">
        <h3 class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">People</h3>
    </div>

    <a href="{{ route('employees.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'employee') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'employee') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128H5.228A2 2 0 0 1 3 17.208V16.5A2 2 0 0 1 5 14.5h.515M15 19.128v-.003c0-.396.078-.774.218-1.116M12 5.38a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm6.75 5.25a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" />
        </svg>
        Employees
    </a>

    @can('view-department')
    <a href="{{ route('departments.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'departments.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'departments.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
        </svg>
        Departments
    </a>
    @endcan

    @can('view-designation')
    <a href="{{ route('designations.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'designations.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'designations.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
        </svg>
        Designations
    </a>
    @endcan

    {{-- ── Workforce ── --}}
    <div class="pt-5 pb-1.5 px-3">
        <h3 class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Workforce</h3>
    </div>

    <a href="{{ route('attendances.dashboard') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'attendances.dashboard' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'attendances.dashboard' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
        </svg>
        Attendance Dashboard
    </a>

    @php
        $isAttendance = str_starts_with($currentRoute, 'attendance') || str_starts_with($currentRoute, 'missing-punch');
    @endphp

    <a href="{{ route('attendances.import.create') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'attendances.import') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.import') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
        </svg>
        Import Attendance
    </a>

    <a href="{{ route('attendances.daily-register') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'attendances.daily-register' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'attendances.daily-register' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        Daily Register
    </a>

    <a href="{{ route('attendances.reports.monthly') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'attendances.reports.monthly' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'attendances.reports.monthly' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        Monthly Register
    </a>

    <a href="{{ route('attendances.corrections.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'attendances.corrections') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.corrections') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>
        Corrections
    </a>

    <a href="{{ route('missing-punches.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'missing-punches') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'missing-punches') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
        </svg>
        Missing Punches
    </a>

    <a href="{{ route('attendances.reports') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'attendances.reports' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'attendances.reports' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        Reports
    </a>

    <div class="pt-2 pb-1">
        <div class="border-t border-slate-700/40 mx-3"></div>
    </div>

    <a href="{{ route('attendances.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'attendances.index') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'attendances.index') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        All Records
    </a>

    <a href="{{ route('leaves.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'leave') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'leave') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        Leave
    </a>

    @can('view-shift')
    <a href="{{ route('shifts.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'shifts.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'shifts.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        Shifts
    </a>
    @endcan

    @can('view-holiday')
    <a href="{{ route('holidays.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'holidays.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'holidays.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        Holidays
    </a>
    @endcan

    {{-- ── Organization ── --}}
    @if($isSuperAdmin)
    <div class="pt-5 pb-1.5 px-3">
        <h3 class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Organization</h3>
    </div>

    <a href="{{ route('organizations.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'organizations.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'organizations.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
        </svg>
        Organizations
    </a>

    <a href="{{ route('branches.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'branches.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'branches.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015A3.001 3.001 0 0 0 21 9.35m-18 0V6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v3.35" />
        </svg>
        Branches
    </a>
    @endif

    {{-- ── Payroll ── --}}
    <div class="pt-5 pb-1.5 px-3">
        <h3 class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Payroll</h3>
    </div>

    <a href="{{ route('payroll.runs.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'payroll.runs') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'payroll.runs') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        Payroll Runs
    </a>

    @if($isSuperAdmin)
    <a href="{{ route('payroll.salary-components.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'payroll.salary-components.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'payroll.salary-components.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        Salary Components
    </a>

    <a href="{{ route('payroll.salary-structures.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ $currentRoute === 'payroll.salary-structures.index' ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $currentRoute === 'payroll.salary-structures.index' ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
        Salary Structures
    </a>
    @endif

    {{-- ── Administration ── --}}
    @if($isSuperAdmin)
    <div class="pt-5 pb-1.5 px-3">
        <h3 class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-600">Administration</h3>
    </div>

    <a href="{{ route('user-management.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'user-management') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'user-management') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
        </svg>
        User Management
    </a>

    <a href="{{ route('settings.index') }}" wire:navigate
       class="group flex items-center gap-3 px-3 py-2 text-[13px] font-medium rounded-btn transition-all duration-150 {{ str_starts_with($currentRoute, 'settings') ? 'bg-primary-600/10 text-primary-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
        <svg class="w-[18px] h-[18px] flex-shrink-0 {{ str_starts_with($currentRoute, 'settings') ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        Settings
    </a>
    @endif
</nav>
