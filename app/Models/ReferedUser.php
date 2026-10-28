<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ReferedUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [ 'refered_user', 'name', 'email', 'phonenumber', 'address', 'process_stage', 'created_at', 'updated_at'];
}
