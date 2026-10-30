<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RosarySubCategory extends Model
{
    protected $table = 'rosary_sub_categories';

    protected $fillable = ['category_id', 'name', 'subtitle', 'image_url', 'sort_order','language_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RosaryCategory::class, 'category_id');
    }

    public function prayers(): HasMany
    {
        return $this->hasMany(RosaryPrayer::class, 'sub_category_id')->orderBy('sort_order');
    }
     public function language(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RosaryLanguage::class, 'language_id');
    }
}