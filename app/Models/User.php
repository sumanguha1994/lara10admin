<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];
    protected $appends = ['avatar'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getAvatarAttribute (){
        if (array_key_exists('profile_image', $this->attributes) && (!empty($this->attributes['profile_image']))) {
            return asset('public/uploads/user/'.$this->attributes['profile_image']);
        } else {
            return asset('public/uploads/user/noimg.png');
        }
    }
}
