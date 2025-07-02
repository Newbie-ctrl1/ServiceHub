<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'text',
        'location',
        'is_active'
    ];

    /**
     * Get the user that owns the banner.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
