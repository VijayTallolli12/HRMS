<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $branding['app_name'] }}</title>
        <link rel="icon" href="{{ $branding['favicon_url'] }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/" wire:navigate>
                    @if($branding['logo_url'])
                        <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['company_name'] }}" class="h-20 w-20 object-contain">
                    @else
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    @endif
                </a>
                <p class="mt-3 text-center text-lg font-semibold text-gray-900">{{ $branding['company_name'] }}</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
