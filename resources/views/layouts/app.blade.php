<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Explorer - Netflix Style</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gray-800 shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('movies.list') }}" class="text-xl font-bold text-red-600">Movie Explorer</a>
            <div class="space-x-4 hidden md:flex">
                <a href="{{ route('movies.list') }}" class="hover:text-red-500">Movies</a>
                @auth
                    <a href="{{ route('favorites.index') }}" class="hover:text-red-500">Favorites</a>
                @endauth
                <a href="{{ route('contact.index') }}" class="hover:text-red-500">Contact</a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="hover:text-red-500">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="px-4 md:px-8 py-6">
        {{-- @yield('content') --}}
        {{ $slot }}
    </main>

    <footer class="text-center text-sm text-gray-500 mt-12 py-6">
        &copy; {{ date('Y') }} Movie Explorer. Built by Mfundo Mthethwa.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('navToggle');
            const menu = document.getElementById('navMenu');
    
            toggle.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>