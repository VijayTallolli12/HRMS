@php
    $user = auth()->user();
    $isSuperAdmin = $user?->isSuperAdmin();
    $currentRoute = request()->route()->getName();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $branding['app_name'] }}</title>
        <link rel="icon" href="{{ $branding['favicon_url'] }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-surface-50">
        <div x-data="{ sidebarOpen: false, sidebarCompact: false }" class="min-h-screen flex">

            {{-- Mobile sidebar overlay --}}
            <div x-show="sidebarOpen" x-cloak
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 lg:hidden">
                <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" x-on:click="sidebarOpen = false"></div>
                <div class="fixed inset-0 flex">
                    <div class="relative w-72 max-w-[calc(100vw-3rem)]"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="-translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="-translate-x-full">
                        <div class="flex h-full flex-col bg-slate-900 overflow-y-auto">
                            <livewire:sidebar-nav />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Desktop sidebar --}}
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-[280px] lg:flex-col">
                <div class="flex h-full flex-col bg-slate-900 overflow-y-auto shadow-sidebar">
                    <livewire:sidebar-nav />
                </div>
            </div>

            {{-- Main content area --}}
            <div class="flex-1 lg:pl-[280px] min-h-screen flex flex-col">

                {{-- Top header bar --}}
                <header class="sticky top-0 z-30 h-[72px] shrink-0 bg-white border-b border-gray-200 shadow-sm">
                    <div class="flex h-full items-center justify-between gap-4 px-5 lg:px-8">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Mobile menu button --}}
                            <button type="button" x-on:click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>

                            {{-- Search input --}}
                            <div class="relative hidden md:flex items-center w-full max-w-[420px]">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                                <input type="text" placeholder="Search employees, payroll, attendance..." class="w-full h-11 pl-12 pr-24 text-sm border border-gray-200 rounded-2xl bg-gray-50 text-gray-700 focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/15 focus:outline-none transition duration-150 placeholder:text-gray-400" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-medium text-gray-500">
                                    <span class="font-semibold">Ctrl</span>+K
                                </span>
                            </div>

                            {{-- Mobile search button --}}
                            <div class="md:hidden" x-data="{ open: false }">
                                <button type="button" x-on:click="open = !open" class="p-2 text-gray-500 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak x-on:click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     class="absolute left-4 top-[72px] z-40 w-[calc(100%-2rem)] rounded-[28px] bg-white border border-gray-200 p-4 shadow-xl">
                                    <div class="relative">
                                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                        <input type="text" placeholder="Search employees, payroll, attendance..." class="w-full h-11 pl-12 pr-4 text-sm border border-gray-200 rounded-2xl bg-gray-50 text-gray-700 focus:border-primary-500 focus:bg-white focus:ring-2 focus:ring-primary-500/15 focus:outline-none transition duration-150 placeholder:text-gray-400" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="hidden sm:inline-flex text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</span>

                            <button type="button" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                </svg>
                                <span class="absolute top-3 right-3 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                            </button>

                            <div class="hidden sm:block h-8 w-px bg-gray-200"></div>

                            @if($isSuperAdmin)
                                <span class="hidden sm:inline-flex h-8 items-center rounded-full bg-primary-50 px-3 text-sm font-medium text-primary-700 border border-primary-100">Super Admin</span>
                            @elseif($user?->isBranchAdmin())
                                <span class="hidden sm:inline-flex h-8 items-center rounded-full bg-sky-50 px-3 text-sm font-medium text-sky-700 border border-sky-100">Branch Admin</span>
                            @endif

                            <div class="relative" x-data="{ open: false }">
                                <button type="button" x-on:click="open = !open" class="inline-flex items-center gap-3 rounded-full hover:bg-gray-100 px-1.5 py-1 transition-colors">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-sm font-semibold text-white shadow-sm">
                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="hidden sm:flex flex-col text-left leading-tight">
                                        <span class="text-sm font-medium text-gray-900">{{ $user->name ?? '' }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->email ?? '' }}</span>
                                    </div>
                                    <svg class="hidden sm:inline-block w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak x-on:click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     class="absolute right-0 z-50 mt-2 w-64 origin-top-right rounded-2xl bg-white border border-gray-100 shadow-dropdown py-2">
                                    <div class="px-4 py-4 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ $user->name ?? '' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $user->email ?? '' }}</p>
                                    </div>
                                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('password.confirm') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                        </svg>
                                        Change Password
                                    </a>
                                    <a href="{{ url('/settings') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5ZM19.5 12a7.5 7.5 0 0 1-1.892 4.937l1.233 1.23a.75.75 0 1 1-1.061 1.06l-1.23-1.233A7.474 7.474 0 0 1 12 19.5a7.474 7.474 0 0 1-4.937-1.892l-1.23 1.233a.75.75 0 0 1-1.06-1.06l1.233-1.23A7.5 7.5 0 0 1 4.5 12a7.5 7.5 0 0 1 1.892-4.937L5.159 5.833a.75.75 0 0 1 1.06-1.06l1.23 1.233A7.474 7.474 0 0 1 12 4.5a7.474 7.474 0 0 1 4.937 1.892l1.23-1.233a.75.75 0 0 1 1.06 1.06l-1.233 1.23A7.5 7.5 0 0 1 19.5 12Z" />
                                        </svg>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors text-left">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                            </svg>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Page content --}}
                <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
                    {{-- Flash messages --}}
                    @if (session('success'))
                        <div class="mb-6 toast-success animate-slide-down"
                             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-6 toast-error animate-slide-down"
                             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <p class="text-sm font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
        <script>
            window.addEventListener('branding-updated', function (event) {
                const branding = event.detail.branding || {};

                if (branding.app_name) {
                    document.title = branding.app_name;
                }

                if (branding.favicon_url) {
                    let favicon = document.querySelector('link[rel="icon"]');

                    if (! favicon) {
                        favicon = document.createElement('link');
                        favicon.rel = 'icon';
                        document.head.appendChild(favicon);
                    }

                    favicon.href = branding.favicon_url;
                }
            });
        </script>
        @stack('scripts')
    </body>
</html>
