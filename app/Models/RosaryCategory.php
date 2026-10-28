<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RosaryCategory extends Model
{
    protected $table = 'rosary_categories';

    protected $fillable = ['slug', 'name', 'icon', 'sort_order'];

    public function prayers(): HasMany
    {
        return $this->hasMany(RosaryPrayer::class, 'category_id')->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}