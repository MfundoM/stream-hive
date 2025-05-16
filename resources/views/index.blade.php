<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-4">Popular Movies</h1>

        <form method="GET" action="{{ route('movies.list') }}" class="mb-6 relative" id="searchForm">
            <input id="search" type="text" name="q"
                placeholder="Search movies..."
                autocomplete="off"
                value="{{ request('q') }}"
                class="w-full p-3 border rounded-md shadow-sm relative z-10 text-gray-800" />

            <ul id="autocomplete-results"
                class="absolute left-0 right-0 bg-gray-800 border rounded shadow z-50 mt-1 hidden max-h-60 overflow-y-auto"></ul>
        </form>

        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse ($movies as $movie)
                <div
                    class="movie-card relative bg-white rounded-xl shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow duration-300"
                    data-movie-id="{{ $movie['id'] }}"
                >
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                         alt="{{ $movie['title'] }}"
                         class="w-full h-[300px] object-cover" />
                
                    <div class="p-4">
                        <h2 class="text-sm text-gray-500 font-semibold">{{ $movie['title'] }}</h2>
                        <p class="text-sm text-gray-500"><strong>Release Date: </strong>{{ $movie['release_date'] }}</p>

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
                                <button type="submit" class="mt-2 text-sm text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded">
                                    Add to Favorites
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            @empty
                <p>No movies found.</p>
            @endforelse
        </div>

        @if ($total_pages > 1)
            <div class="mt-8 flex justify-center flex-wrap gap-2">
                @for ($i = 1; $i <= $total_pages; $i++)
                    <a href="{{ route('movies.list', ['page' => $i] + request()->only('q')) }}"
                       class="px-4 py-2 rounded shadow-sm {{ $i == $page ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100 text-gray-700' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div id="movieModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg max-w-3xl w-full p-6 relative max-h-[80vh] overflow-auto">
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-2xl font-bold" aria-label="Close modal">&times;</button>
            <div id="modalContent" class="space-y-4">
                <p class="text-center text-gray-500">Loading...</p>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('movieModal');
            const modalContent = document.getElementById('modalContent');
            const closeModalBtn = document.getElementById('closeModalBtn');

            document.querySelectorAll('.movie-card').forEach(card => {
                card.addEventListener('click', async function () {
                    const movieId = this.dataset.movieId;
                    modalContent.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    try {
                        const response = await fetch(`/movies/${movieId}/details`);
                        if (!response.ok) throw new Error('Movie not found');
                        const data = await response.json();
                        console.log(data);

                        modalContent.innerHTML = `
                            <h2 class="text-2xl font-bold mb-4">${data.movie.title}</h2>
                            ${data.trailer_key 
                                ? `<iframe
                                    class="w-full h-64 mb-2"
                                    src="https://www.youtube.com/embed/${data.trailer_key}?autoplay=1&mute=1&controls=1"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen></iframe>`
                                : `<img 
                                    src="https://image.tmdb.org/t/p/w500${data.movie.poster_path}" 
                                    alt="${data.movie.title}" 
                                    class="w-full max-h-96 object-contain mb-2" />`
                            }
                            <p class="pt-0 text-gray-700"><strong>Release Date:</strong> ${data.movie.release_date}</p>
                            <p class="pt-0 text-gray-700">${data.movie.overview || 'No description available.'}</p>
                        `;
                    } catch (error) {
                        modalContent.innerHTML = `<p class="text-center text-red-600">Failed to load movie details.</p>`;
                    }
                });
            });

            // Close modal handlers
            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modalContent.innerHTML = '';
                document.body.style.overflow = '';
            });
            modal.addEventListener('click', e => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modalContent.innerHTML = '';
                    document.body.style.overflow = '';
                }
            });

            // Search autocomplete
            const searchInput = document.getElementById("search");
            const resultsBox = document.getElementById("autocomplete-results");
            const form = document.getElementById("searchForm");

            searchInput.addEventListener("input", function () {
                const query = this.value.trim();
                if (!query) {
                    resultsBox.classList.add("hidden");
                    resultsBox.innerHTML = "";
                    return;
                }

                fetch(`/api/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsBox.innerHTML = "";
                        if (data.results.length === 0) {
                            resultsBox.classList.add("hidden");
                            return;
                        }

                        data.results.slice(0, 5).forEach(movie => {
                            const li = document.createElement("li");
                            li.textContent = movie.title;
                            li.className = "px-4 py-2 hover:bg-gray-100 hover:text-gray-600 cursor-pointer";
                            li.addEventListener("click", () => {
                                searchInput.value = movie.title;
                                resultsBox.classList.add("hidden");
                                form.submit();
                            });
                            resultsBox.appendChild(li);
                        });

                        resultsBox.classList.remove("hidden");
                    });
            });

            document.addEventListener("click", (e) => {
                if (!form.contains(e.target)) {
                    resultsBox.classList.add("hidden");
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.add-to-favorites-form').forEach(form => {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
    
                    const movieId = this.dataset.movieId;
                    const title = this.dataset.title;
                    const poster = this.dataset.poster;
                    const release = this.dataset.release;
                    const token = this.querySelector('input[name="_token"]').value;
    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                movie_id: movieId,
                                title: title,
                                poster_path: poster,
                                release_date: release
                            })
                        });
    
                        const data = await response.json();
    
                        if (!response.ok) throw new Error(data.message || 'Failed to add to favorites');
                        
                        alert(data.message || 'Movie added to favorites!');
                    } catch (error) {
                        alert(error.message);
                    }
                });
            });
        });
    </script>
    
</x-app-layout>
