<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TmdbController extends Controller
{
    public function __construct(private TmdbService $tmdb) {}

    public function index(Request $request)
    {
        $uiPage = max((int)$request->get('page', 1), 1);
        $query = $request->get('q');

        $perPage = 9;
        $maxMovies = 45;
        $maxUiPages = (int) ceil($maxMovies / $perPage);

        if ($uiPage > $maxUiPages) {
            $uiPage = $maxUiPages;
        }

        $pagesToFetch = 3;
        $allMovies = [];
        for ($i = 1; $i <= $pagesToFetch; $i++) {
            $cacheKey = $query
                ? "search_movies_page_{$i}_query_" . md5($query)
                : "popular_movies_page_{$i}";

            $response = Cache::remember($cacheKey, now()->addHours(2), function () use ($query, $i) {
                return $query
                    ? $this->tmdb->searchMovies($query, $i)
                    : $this->tmdb->getPopularMovies($i);
            });

            if (empty($response['results'])) {
                break;
            }

            $allMovies = array_merge($allMovies, $response['results']);

            if (count($allMovies) >= $maxMovies) {
                break;
            }
        }

        $allMovies = array_slice($allMovies, 0, $maxMovies);
        // dd($allMovies);

        $moviesForPage = array_slice($allMovies, ($uiPage - 1) * $perPage, $perPage);

        return view('index', [
            'movies' => $moviesForPage,
            'page' => $uiPage,
            'total_pages' => $maxUiPages,
            'query' => $query,
            'favoriteMovieIds' => Auth::check()
                ? Favorite::where('user_id', Auth::id())->pluck('movie_id')->toArray()
                : [],
        ]);
    }

    public function movieDetails($id)
    {
        $movie = Cache::remember("movie_details_{$id}", now()->addHours(4), function () use ($id) {
            return $this->tmdb->getMovieDetails($id);
        });

        $videos = Cache::remember("movie_trailers_{$id}", now()->addHours(4), function () use ($id) {
            return $this->tmdb->getMovieTrailer($id);
        });

        $trailer = collect($videos['results'] ?? [])->firstWhere('type', 'Trailer');

        return response()->json([
            'movie' => $movie,
            'trailer_key' => $trailer['key'] ?? null,
        ]);
    }
}
