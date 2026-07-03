<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
            <a href="/" class="flex items-center gap-2 text-indigo-600">
                <x-application-logo class="w-12 h-12" />
                <span class="text-2xl font-bold text-gray-900 tracking-tight">GestorPro</span>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-gray-200 sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
