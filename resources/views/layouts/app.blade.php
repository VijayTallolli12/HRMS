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
        <title>{{ config('app.name', 'HRMS') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">

            {{-- Mobile sidebar overlay --}}
            <div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-gray-900/80" x-on:click="sidebarOpen = false"></div>
                <div class="fixed inset-0 flex">
                    <div class="relative mr-16 flex w-full max-w-xs flex-1" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
                            @include('components._sidebar-nav')
                        </div>
                        <div class="absolute right-0 top-0 -mr-2 flex w-10 items-center justify-center">
                            <button type="button" x-on:click="sidebarOpen = false" class="text-gray-400 hover:text-white">
                                <x-heroicon name="x-mark" class="w-6 h-6" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Desktop sidebar --}}
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
                    @include('components._sidebar-nav')
                </div>
            </div>

            {{-- Main content --}}
            <div class="lg:pl-72">
                {{-- Top header --}}
                <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    <button type="button" x-on:click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                        <x-heroicon name="bars-3" class="h-5 w-5" />
                    </button>
                    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>
                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex flex-1"></div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            @if($isSuperAdmin)
                                <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Super Admin</span>
                            @elseif($user?->isBranchAdmin())
                                <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">Branch Admin</span>
                            @endif
                            <div class="relative" x-data="{ open: false }">
                                <button type="button" x-on:click="open = !open" class="flex items-center gap-x-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-200">
                                        <span class="text-xs font-bold text-gray-600">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                                    </span>
                                    <span class="hidden sm:block">{{ $user->name ?? '' }}</span>
                                    <x-heroicon name="chevron-down" class="hidden sm:block h-4 w-4 text-gray-400" />
                                </button>
                                <div x-show="open" x-cloak x-on:click.away="open = false" x-transition class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:navigate>Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Page content --}}
                <main class="py-6">
                    <div class="px-4 sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                                <x-heroicon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 flex items-center gap-2" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                                <x-heroicon name="x-circle" class="w-5 h-5 flex-shrink-0" />
                                {{ session('error') }}
                            </div>
                        @endif
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
