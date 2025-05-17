<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();

        return view('favorites', compact('favorites'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'movie_id' => 'required|integer',
                'title' => 'required|string',
                'poster_path' => 'nullable|string',
                'release_date' => 'nullable|string',
            ]);

            Favorite::firstOrCreate([
                'user_id' => Auth::id(),
                'movie_id' => $request->movie_id,
            ], $request->only('title', 'poster_path', 'release_date'));

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Movie added to favorites!']);
            }

            return redirect()->back()->with('success', 'Movie added to favorites.');
        } catch (\Exception $e) {
            Log::error('Failed to add favorite: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'An error occurred while adding to favorites.'], 500);
            }

            return back()->with('error', 'An error occurred while adding to favorites.');
        }
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id === Auth::id()) {
            $favorite->delete();
        }

        return back()->with('success', 'Movie removed from favorites.');
    }
}
