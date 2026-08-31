<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'description',
        'price', 'is_negotiable', 'stock', 'condition', 'status', 'address',
    ];

    protected $casts = [
        'price'         => 'decimal:0',
        'is_negotiable' => 'boolean',
        'stock'         => 'integer',
    ];

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // --- Helpers ---

    public function incrementView(): void
    {
        $this->increment('view_count');
    }

    public function getWhatsappUrl(): string
    {
        $phone = preg_replace('/^0/', '62', $this->user->store_wa ?? '');
        $text  = urlencode("Halo, saya tertarik dengan iklan \"{$this->title}\"");
        return "https://wa.me/{$phone}?text={$text}";
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // --- Relationships ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function firstImage()
    {
        return $this->hasOne(ListingImage::class)->orderBy('sort_order');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
