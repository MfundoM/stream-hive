<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TmdbController;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Route;

Route::get('/', [TmdbController::class, 'index'])->name('movies.list');

Route::get('/api/search', function (\Illuminate\Http\Request $request, TmdbService $tmdb) {
    return response()->json($tmdb->searchMovies($request->query('q')));
});

Route::get('/movies/{id}/details', [TmdbController::class, 'movieDetails'])->name('movies.details');

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

require __DIR__ . '/auth.php';
