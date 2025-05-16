<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TmdbService
{
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
    }

    protected function get(string $endpoint, array $params = [])
    {
        $params = array_merge($params, [
            'api_key' => $this->apiKey,
            'language' => 'en-US'
        ]);

        try {
            $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

            if ($response->failed()) {
                throw new \Exception('Failed to fetch data from TMDB: ' . $response->body());
            }

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('TMDB API Error: ' . $e->getMessage());

            throw new \Exception('An error occurred while communicating with TMDB.');
        }
    }

    public function getPopularMovies(int $page = 1)
    {
        return $this->get('movie/popular', [
            'page' => $page,
        ]);
    }

    public function searchMovies(string $query, int $page = 1)
    {
        return $this->get('search/movie', [
            'query' => $query,
            'page' => $page,
        ]);
    }

    public function getMovieDetails(int $id)
    {
        return $this->get("movie/{$id}");
    }

    public function getMovieTrailer(int $id)
    {
        return $this->get("movie/{$id}/videos");
    }
}
