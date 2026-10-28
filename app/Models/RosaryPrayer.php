<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RosaryPrayer extends Model
{
    protected $table = 'rosary_prayers';

    protected $fillable = [
        'category_id', 'slug', 'title', 'subtitle', 'body',
        'thumbnail_url', 'hero_image_url', 'audio_url',
        'duration_seconds', 'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RosaryCategory::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}