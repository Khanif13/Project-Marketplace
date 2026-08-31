<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'role', 'seller_status', 'store_name', 'store_address', 'store_wa',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // --- Helpers ---

    public function isSeller(): bool
    {
        return $this->role === 'seller' && $this->seller_status === 'verified';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPendingSeller(): bool
    {
        return $this->seller_status === 'pending';
    }

    // --- Relationships ---

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function bookmarkedListings()
    {
        return $this->belongsToMany(Listing::class, 'bookmarks')->withTimestamps();
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function sellerVerification()
    {
        return $this->hasOne(SellerVerification::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
