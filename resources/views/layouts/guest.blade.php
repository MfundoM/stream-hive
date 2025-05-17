<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'StreamHive') }}</title>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-black text-white min-h-screen font-sans antialiased">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-md bg-[#141414] p-8 rounded-lg shadow-lg border border-gray-800">
                <div class="flex justify-center mb-6">
                    <a href="/">
                        <img src="{{ asset('logo.png') }}" alt="StreamHive Logo" class="h-12">
                    </a>
                </div>

                {{ $slot }}
            </div>
        </div>
    </body>
</html>
