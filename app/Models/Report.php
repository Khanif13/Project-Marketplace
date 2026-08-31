<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['reporter_id', 'listing_id', 'reason', 'description', 'status'];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
