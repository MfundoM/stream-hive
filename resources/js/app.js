import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // ========== Modal ==========
    const modal = document.getElementById('movieModal');
    const modalContent = document.getElementById('modalContent');
    const closeModalBtn = document.getElementById('closeModalBtn');

    if (modal && modalContent && closeModalBtn) {
        // document.querySelectorAll('.movie-card').forEach(card => {
        document.querySelectorAll('[data-movie-id]').forEach(card => {
            card.addEventListener('click', async function () {
                const movieId = this.dataset.movieId;
                modalContent.innerHTML = '<p class="text-center text-gray-500">Loading...</p>';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                try {
                    const response = await fetch(`/movies/${movieId}/details`);
                    console.log(response)
                    if (!response.ok) {
                        console.log(response.message)
                        throw new Error('Movie not found');
                    }
                    const data = await response.json();

                    modalContent.innerHTML = `
                        <h2 class="text-2xl font-bold mb-4 text-gray-200">${data.movie.title}</h2>
                        ${data.trailer_key
                            ? `<iframe class="w-full h-64 mb-2" style="height: 16rem;" src="https://www.youtube.com/embed/${data.trailer_key}?autoplay=1&mute=1&controls=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`
                            : `<img src="https://image.tmdb.org/t/p/w500${data.movie.poster_path}" alt="${data.movie.title}" class="w-full max-h-96 object-contain mb-2" style="max-height: 384px; object-fit: contain;" />`
                        }
                        <p class="pt-0 text-gray-200"><strong>Release Date:</strong> ${data.movie.release_date}</p>
                        <p class="pt-0 text-gray-200">${data.movie.overview || 'No description available.'}</p>
                    `;
                } catch (error) {
                    modalContent.innerHTML = `<p class="text-center text-red-600">Failed to load movie details.</p>`;
                }
            });
        });

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
    }

    // ========== Autocomplete ==========
    const searchInput = document.getElementById("search");
    const resultsBox = document.getElementById("autocomplete-results");
    const form = document.getElementById("searchForm");

    if (searchInput && resultsBox && form) {
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
    }

    // ========== Add to Favorites ==========
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
                console.log(data);
                if (!response.ok) {
                    console.log(data.message);
                    throw new Error('Failed to add to favorites');
                }

                const button = this.querySelector('button');
                button.innerHTML = `<span class="inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.343l-6.828-6.829a4 4 0 010-5.656z" />
                                        </svg>
                                         Favorited
                                    </span>`;

                button.disabled = true;
                button.classList.remove('bg-red-600', 'hover:bg-red-700');
                button.classList.add('bg-gray-600', 'cursor-not-allowed');

                // alert(data.message);
            } catch (error) {
                alert(error.message);
            }
        });
    });
});

