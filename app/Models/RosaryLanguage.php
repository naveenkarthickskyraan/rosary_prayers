<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosaryLanguage extends Model
{
    protected $table = 'rosary_languages';

    protected $fillable = ['lang_name', 'lang_code'];
}