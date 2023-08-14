<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagecontent extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['avatar'];

    public function getAvatarAttribute (){
        if (array_key_exists('page_image', $this->attributes) && (!empty($this->attributes['page_image']))) {
            return asset('public/uploads/page/'.$this->attributes['page_image']);
        } else {
            return asset('public/uploads/page/noimg.png');
        }
    }

    public function page(){
        return $this->belongsTo(Page::class);
    }
}
