<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 text-white bg-gray-900 min-h-screen">
        <h1 class="text-3xl font-bold mb-6 text-red-600">My Favorites</h1>

        <!-- Success Notification -->
        <div 
            x-data="{ show: {{ session('success') ? 'true' : 'false' }} }" 
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition
            class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow-lg z-50"
            style="display: none;">
            {{ session('success') }}
        </div>

        <!-- Movie Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-6">
            @forelse ($favorites as $movie)
                <div class="relative rounded-lg overflow-hidden group shadow-lg transform hover:scale-105 transition-transform duration-300 cursor-pointer"
                    data-movie-id="{{ $movie->movie_id }}">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
                         alt="{{ $movie->title }}"
                         class="w-full h-[300px] object-cover rounded-lg group-hover:opacity-75 transition duration-300" />

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                        <h2 class="text-sm font-semibold truncate">{{ $movie->title }}</h2>
                        <p class="text-xs text-gray-300 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $movie->release_date }}
                        </p>

                        <form action="{{ route('favorites.destroy', $movie) }}" method="POST" class="mt-2" @click.stop>
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="event.stopPropagation();"
                                    class="text-xs text-white flex items-center gap-1 bg-red-600 hover:bg-red-700 px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a1 1 0 001-1V5a1 1 0 00-1-1h-2.5a1 1 0 01-.707-.293l-.707-.707A1 1 0 0012.5 3h-1a1 1 0 00-.707.293l-.707.707A1 1 0 019.5 4H7a1 1 0 00-1 1v1a1 1 0 001 1h10z" />
                                </svg> Remove
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-300">No favorite movies added.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div id="movieModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-900 rounded-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-auto text-white shadow-xl border border-gray-700">
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl font-bold" aria-label="Close modal">&times;</button>
            <div id="modalContent" class="space-y-4">
                <p class="text-center text-gray-400">Loading...</p>
            </div>
        </div>
    </div>
</x-app-layout>
