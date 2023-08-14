<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['avatar'];

    public function getAvatarAttribute (){
        if (array_key_exists('image', $this->attributes) && (!empty($this->attributes['image']))) {
            return asset('public/uploads/banner/'.$this->attributes['image']);
        } else {
            return asset('public/uploads/banner/noimg.png');
        }
    }
}
