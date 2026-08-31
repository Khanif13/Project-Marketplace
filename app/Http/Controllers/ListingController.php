<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    // -------------------------------------------------------
    // PUBLIC: Homepage
    // -------------------------------------------------------
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $latestListings = Listing::with(['firstImage', 'user'])
            ->active()
            ->latest()
            ->take(8)
            ->get();

        $popularListings = Listing::with(['firstImage', 'user'])
            ->active()
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        $totalListings = Listing::active()->count();
        $totalSellers = User::where('role', 'seller')
            ->where('seller_status', 'verified')
            ->count();
        $totalCategories = Category::whereNull('parent_id')->count();

        return view('home', compact(
            'categories',
            'latestListings',
            'popularListings',
            'totalListings',
            'totalSellers',
            'totalCategories'
        ));
    }

    // -------------------------------------------------------
    // PUBLIC: Search & Filter
    // -------------------------------------------------------
    public function search(Request $request)
    {
        $query = Listing::with(['firstImage', 'user', 'category'])
            ->active();

        // Keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Kategori
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                // Ambil juga sub-kategori
                $ids = $category->children->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        // Kondisi
        if ($request->filled('condition') && in_array($request->condition, ['new', 'used'])) {
            $query->where('condition', $request->condition);
        }

        // Harga
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) str_replace('.', '', $request->price_min));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) str_replace('.', '', $request->price_max));
        }

        // Nego
        if ($request->boolean('negotiable')) {
            $query->where('is_negotiable', true);
        }

        // Sort
        match ($request->get('sort', 'latest')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderByDesc('view_count'),
            default => $query->latest(),
        };

        $listings = $query->paginate(16)->withQueryString();
        $categories = Category::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('listings.search', compact('listings', 'categories'));
    }

    // -------------------------------------------------------
    // PUBLIC: Listing by Category
    // -------------------------------------------------------
    public function byCategory(Category $category)
    {
        $ids = $category->children->pluck('id')->push($category->id);

        $listings = Listing::with(['firstImage', 'user'])
            ->active()
            ->whereIn('category_id', $ids)
            ->latest()
            ->paginate(16);

        $categories = Category::whereNull('parent_id')->orderBy('sort_order')->get();

        return view('listings.category', compact('listings', 'category', 'categories'));
    }

    // -------------------------------------------------------
    // PUBLIC: Detail Iklan
    // -------------------------------------------------------
    public function show(Listing $listing)
    {
        // Hanya tampilkan iklan aktif atau kosong — bukan inactive
        abort_if($listing->status === 'inactive', 404);

        // Increment view
        $listing->incrementView();

        $listing->load(['images', 'user', 'category']);

        // Iklan lain dari seller yang sama
        $sellerListings = Listing::with('firstImage')
            ->active()
            ->where('user_id', $listing->user_id)
            ->where('id', '!=', $listing->id)
            ->latest()
            ->take(4)
            ->get();

        // Iklan serupa (same category)
        $relatedListings = Listing::with('firstImage')
            ->active()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->latest()
            ->take(4)
            ->get();

        $isBookmarked = Auth::check()
            && Auth::user()->bookmarkedListings->contains($listing->id);

        return view('listings.show', compact(
            'listing',
            'sellerListings',
            'relatedListings',
            'isBookmarked'
        ));
    }

    // -------------------------------------------------------
    // SELLER: Form Buat Iklan
    // -------------------------------------------------------
    public function create()
    {
        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->with('parent')
            ->get()
            ->groupBy(fn ($c) => $c->parent->name);

        return view('listings.create', compact('categories'));
    }

    // -------------------------------------------------------
    // SELLER: Simpan Iklan Baru
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0',
            'is_negotiable' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            'condition' => 'required|in:new,used',
            'address' => 'required|string|max:255',
            'images' => 'required|array|min:1|max:6',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $listing = Auth::user()->listings()->create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_negotiable' => $request->boolean('is_negotiable'),
            'stock' => $validated['stock'] ?? null,
            'condition' => $validated['condition'],
            'address' => $validated['address'],
            'status' => 'active',
        ]);

        // Upload gambar
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('listings', 'public');
            $listing->images()->create([
                'path' => $path,
                'sort_order' => $index,
            ]);
        }

        return redirect()
            ->route('listings.show', $listing->slug)
            ->with('success', 'Iklan berhasil dipasang!');
    }

    // -------------------------------------------------------
    // SELLER: Form Edit Iklan
    // -------------------------------------------------------
    public function edit(Listing $listing)
    {
        abort_if(! $listing->isOwnedBy(Auth::user()), 403);

        $categories = Category::whereNotNull('parent_id')
            ->orderBy('sort_order')
            ->with('parent')
            ->get()
            ->groupBy(fn ($c) => $c->parent->name);

        $listing->load('images');

        return view('listings.edit', compact('listing', 'categories'));
    }

    // -------------------------------------------------------
    // SELLER: Update Iklan
    // -------------------------------------------------------
    public function update(Request $request, Listing $listing)
    {
        abort_if(! $listing->isOwnedBy(Auth::user()), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'price' => 'required|numeric|min:0',
            'is_negotiable' => 'boolean',
            'stock' => 'nullable|integer|min:0',
            'condition' => 'required|in:new,used',
            'address' => 'required|string|max:255',
            'images' => 'nullable|array|max:6',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $listing->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'is_negotiable' => $request->boolean('is_negotiable'),
            'stock' => $validated['stock'] ?? null,
            'condition' => $validated['condition'],
            'address' => $validated['address'],
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('images')) {
            // Hapus gambar lama
            foreach ($listing->images as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('listings', 'public');
                $listing->images()->create([
                    'path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('listings.show', $listing->slug)
            ->with('success', 'Iklan berhasil diperbarui!');
    }

    // -------------------------------------------------------
    // SELLER: Update Status (active / empty / inactive)
    // -------------------------------------------------------
    public function updateStatus(Request $request, Listing $listing)
    {
        abort_if(! $listing->isOwnedBy(Auth::user()), 403);

        $request->validate([
            'status' => 'required|in:active,empty,inactive',
        ]);

        $listing->update(['status' => $request->status]);

        $label = match ($request->status) {
            'active' => 'Iklan diaktifkan.',
            'empty' => 'Iklan ditandai stok habis.',
            'inactive' => 'Iklan dinonaktifkan.',
        };

        return back()->with('success', $label);
    }

    // -------------------------------------------------------
    // SELLER: Hapus Iklan
    // -------------------------------------------------------
    public function destroy(Listing $listing)
    {
        abort_if(! $listing->isOwnedBy(Auth::user()), 403);

        // Hapus semua gambar dari storage
        foreach ($listing->images as $img) {
            Storage::disk('public')->delete($img->path);
        }

        $listing->delete(); // soft delete

        return redirect()
            ->route('dashboard')
            ->with('success', 'Iklan berhasil dihapus.');
    }

    // -------------------------------------------------------
    // Helper: Buat slug unik
    // -------------------------------------------------------
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base.'-'.Str::lower(Str::random(6));

        // Pastikan benar-benar unik
        while (Listing::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(6));
        }

        return $slug;
    }
}
