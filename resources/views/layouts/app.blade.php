<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'StreamHive') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="bg-gray-900 text-white min-h-screen">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <main class="px-4 md:px-8 py-6">
            {{ $slot }}
        </main>

        <footer class="text-center text-sm text-gray-500 mt-12 py-6">
            &copy; {{ date('Y') }} StreamHive. Built by Mfundo Mthethwa.
        </footer>
    </body>
</html>