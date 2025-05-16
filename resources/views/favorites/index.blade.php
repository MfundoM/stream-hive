<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-4">My Favorites</h1>
    
        @if (session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif
    
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @forelse ($favorites as $movie)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
                         alt="{{ $movie->title }}"
                         class="w-full h-[300px] object-cover" />
    
                    <div class="p-4">
                        <h2 class="text-lg font-semibold">{{ $movie->title }}</h2>
                        <p class="text-sm text-gray-500">{{ $movie->release_date }}</p>
    
                        <form action="{{ route('favorites.destroy', $movie) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-sm text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p>No favorite movies added yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>