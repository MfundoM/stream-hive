<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;

class TmdbController extends Controller
{
    public function __construct(private TmdbService $tmdb) {}

    public function index(Request $request)
    {
        $uiPage = max((int)$request->get('page', 1), 1);
        $query = $request->get('q');

        // The number of movies per page
        $perPage = 9;
        $maxMovies = 45;
        $maxUiPages = (int) ceil($maxMovies / $perPage);

        // Limit to the allowed max 
        if ($uiPage > $maxUiPages) {
            $uiPage = $maxUiPages;
        }

        // 3 pages = 60 movies, 20 per page)
        $pagesToFetch = 3;
        $allMovies = [];
        for ($i = 1; $i <= $pagesToFetch; $i++) {
            $response = $query
                ? $this->tmdb->searchMovies($query, $i)
                : $this->tmdb->getPopularMovies($i);

            if (empty($response['results'])) {
                break;
            }

            $allMovies = array_merge($allMovies, $response['results']);

            if (count($allMovies) >= $maxMovies) {
                break;
            }
        }

        // Limit movies to maxMovies (45)
        $allMovies = array_slice($allMovies, 0, $maxMovies);
        // dd($allMovies);

        // Paginate UI results manually: slice the right 9 movies for current UI page
        $moviesForPage = array_slice($allMovies, ($uiPage - 1) * $perPage, $perPage);

        return view('index', [
            'movies' => $moviesForPage,
            'page' => $uiPage,
            'total_pages' => $maxUiPages,
            'query' => $query,
        ]);
    }

    public function movieDetails($id)
    {
        $movie = $this->tmdb->getMovieDetails($id);

        $videos = $this->tmdb->getMovieTrailer($id);

        // Filter YouTube trailers
        $trailer = collect($videos['results'] ?? [])
            ->firstWhere('type', 'Trailer');

        return response()->json([
            'movie' => $movie,
            'trailer_key' => $trailer['key'] ?? null,
        ]);
    }
}
