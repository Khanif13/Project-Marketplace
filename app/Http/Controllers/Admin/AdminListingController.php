<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with(['user', 'category', 'firstImage'])->latest();

        if ($request->filled('q')) {
            $query->where('title', 'like', "%{$request->q}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $listings = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Listing::count(),
            'active' => Listing::where('status', 'active')->count(),
            'empty' => Listing::where('status', 'empty')->count(),
            'inactive' => Listing::where('status', 'inactive')->count(),
        ];

        return view('admin.listings.index', compact('listings', 'stats'));
    }

    public function show(Listing $listing)
    {
        $listing->load(['images', 'user', 'category', 'reports']);

        return view('admin.listings.show', compact('listing'));
    }

    public function updateStatus(Request $request, Listing $listing)
    {
        $request->validate([
            'status' => 'required|in:active,empty,inactive',
        ]);

        $listing->update(['status' => $request->status]);

        return back()->with('success', 'Status iklan berhasil diperbarui.');
    }

    public function destroy(Listing $listing)
    {
        foreach ($listing->images as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $listing->delete();

        return redirect()
            ->route('admin.listings.index')
            ->with('success', 'Iklan berhasil dihapus.');
    }
}
