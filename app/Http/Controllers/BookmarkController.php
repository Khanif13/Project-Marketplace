<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // -------------------------------------------------------
    // Toggle bookmark (add / remove)
    // -------------------------------------------------------
    public function toggle(Listing $listing)
    {
        $user = Auth::user();

        $exists = $user->bookmarks()->where('listing_id', $listing->id)->exists();

        if ($exists) {
            $user->bookmarks()->where('listing_id', $listing->id)->delete();
            $bookmarked = false;
        } else {
            $user->bookmarks()->create(['listing_id' => $listing->id]);
            $bookmarked = true;
        }

        // Kalau request AJAX (dari fetch/axios)
        if (request()->expectsJson()) {
            return response()->json([
                'bookmarked' => $bookmarked,
                'message' => $bookmarked ? 'Iklan disimpan.' : 'Iklan dihapus dari simpanan.',
            ]);
        }

        return back()->with('success', $bookmarked ? 'Iklan disimpan.' : 'Iklan dihapus dari simpanan.');
    }

    // -------------------------------------------------------
    // Halaman daftar bookmark user
    // -------------------------------------------------------
    public function index()
    {
        $listings = Auth::user()
            ->bookmarkedListings()
            ->with(['firstImage', 'user', 'category'])
            ->latest('bookmarks.created_at')
            ->paginate(12);

        return view('bookmarks.index', compact('listings'));
    }
}
