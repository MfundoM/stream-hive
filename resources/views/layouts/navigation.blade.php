<nav x-data="{ open: false }" class="bg-black text-white border-b border-gray-800">
    <!-- Primary Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('movies.list') }}" class="text-2xl font-bold text-red-600">
                    <img src="{{ asset('logo.png') }}" title="Streamhive Logo" class="h-12">
                </a>
                <div class="hidden md:flex space-x-4">
                    <a href="{{ route('movies.list') }}" class="hover:text-red-500 {{ request()->routeIs('movies.list') ? 'active' : '' }}">Movies</a>
                    @auth
                        <a href="{{ route('favorites.index') }}" class="hover:text-red-500 {{ request()->routeIs('favorites.index') ? 'active' : '' }}">Favorites</a>
                    @endauth
                    <a href="{{ route('contact.index') }}" class="hover:text-red-500 {{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a>
                </div>
            </div>

            <!-- Right Section -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-red-500 text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500 text-sm">Login</a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="md:hidden px-4 pt-2 pb-4 space-y-2">
        <a href="{{ route('movies.list') }}" class="block hover:text-red-500 {{ request()->routeIs('movies.list') ? 'active' : '' }}">Movies</a>
        @auth
            <a href="{{ route('favorites.index') }}" class="block hover:text-red-500 {{ request()->routeIs('favorites.index') ? 'active' : '' }}">Favorites</a>
        @endauth
        <a href="{{ route('contact.index') }}" class="block hover:text-red-500 {{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block w-full text-left hover:text-red-500">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block hover:text-red-500">Login</a>
        @endauth
    </div>
</nav>
