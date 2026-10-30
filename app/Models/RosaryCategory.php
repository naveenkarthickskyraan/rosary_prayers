<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RosaryCategory extends Model
{
    protected $table = 'rosary_categories';

    protected $fillable = ['language_id', 'slug', 'name', 'icon', 'sort_order'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(RosaryLanguage::class, 'language_id');
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(RosarySubCategory::class, 'category_id')->orderBy('sort_order');
    }

    public function prayers(): HasMany
    {
        return $this->hasMany(RosaryPrayer::class, 'category_id')->orderBy('sort_order');
    }
}