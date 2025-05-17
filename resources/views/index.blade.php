<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 text-white bg-gray-900 min-h-screen">
        <h1 class="text-3xl font-bold mb-6 text-red-600">Popular Movies</h1>

        <!-- Search -->
        <form method="GET" action="{{ route('movies.list') }}" class="mb-6 relative" id="searchForm">
            <input id="search" type="text" name="q"
                placeholder="Search movies..."
                autocomplete="off"
                value="{{ request('q') }}"
                class="w-full p-3 bg-gray-800 border border-gray-700 rounded-md shadow-sm text-white placeholder-gray-400" />

            <ul id="autocomplete-results"
                class="absolute left-0 right-0 bg-gray-800 border border-gray-600 rounded shadow z-50 mt-1 hidden max-h-60 overflow-y-auto text-white"></ul>
        </form>

        <!-- Movie Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-6">
            @forelse ($movies as $movie)
                <div class="relative rounded-lg overflow-hidden group shadow-lg transform hover:scale-105 transition-transform duration-300 cursor-pointer"
                    data-movie-id="{{ $movie['id'] }}">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                         alt="{{ $movie['title'] }}"
                         class="w-full h-[300px] object-cover rounded-lg group-hover:opacity-75 transition duration-300" />

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                        <h2 class="text-sm font-semibold truncate">{{ $movie['title'] }}</h2>
                        <p class="text-xs text-gray-300 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $movie['release_date'] }}
                        </p>

                        @php
                            $isFavorited = in_array($movie['id'], $favoriteMovieIds ?? []);
                        @endphp
                        @auth
                            <form
                                action="{{ route('favorites.store') }}"
                                method="POST"
                                class="mt-2 add-to-favorites-form"
                                data-movie-id="{{ $movie['id'] }}"
                                data-title="{{ $movie['title'] }}"
                                data-poster="{{ $movie['poster_path'] }}"
                                data-release="{{ $movie['release_date'] }}"
                                @click.stop
                            >
                                @csrf
                                <button
                                    onclick="event.stopPropagation();"
                                    type="submit"
                                    class="text-xs text-white px-3 py-1 rounded-full {{ $isFavorited ? 'bg-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700' }}"
                                    {{ $isFavorited ? 'disabled' : '' }}
                                >
                                    {!! $isFavorited
                                        ? '<span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.343l-6.828-6.829a4 4 0 010-5.656z"/></svg> Favorited</span>'
                                        : '<span class="inline-flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Favorite</span>'
                                    !!}
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            @empty
                <p class="text-white">No movies found.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($total_pages > 1)
            <div class="mt-10 flex justify-center flex-wrap gap-2">
                @for ($i = 1; $i <= $total_pages; $i++)
                    <a href="{{ route('movies.list', ['page' => $i] + request()->only('q')) }}"
                       class="px-4 py-2 rounded shadow-sm text-sm {{ $i == $page ? 'bg-red-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-gray-300' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div id="movieModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-900 rounded-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-auto text-white shadow-xl border border-gray-700">
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-400 hover:text-white text-2xl font-bold" aria-label="Close modal">&times;</button>
            <div id="modalContent" class="space-y-4">
                <p class="text-center text-gray-500">Loading...</p>
            </div>
        </div>
    </div>
</x-app-layout>
