<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RosaryPrayer extends Model
{
    protected $table = 'rosary_prayers';

    protected $fillable = [
        'language_id', 'category_id', 'sub_category_id', 'slug',
        'title', 'subtitle', 'heading', 'body', 'thumbnail_url', 'sort_order',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(RosaryLanguage::class, 'language_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(RosaryCategory::class, 'category_id');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(RosarySubCategory::class, 'sub_category_id');
    }
}