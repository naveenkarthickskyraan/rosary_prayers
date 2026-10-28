<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Question;

class admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
        'accountAccessModule',
        'accountAccessPrivilege',
        'image_type',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function admin()
    // {
    //     return $this->belongsTo('App\Models\Question','user_id','id');
    // }
    // public function posts()
    // {
        /**
         * Get all of the comments for the admin
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        // public function count()
        // {
        //     return $this->hasMany('App\Models\Question', 'admin_id','id');
        // }
        // return $this->hasMany('App\Models\Question','admin_id','id');
    // }

}
