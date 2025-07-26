<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Onsen extends Model
{
    protected $fillable = ['name', 'description', 'prefecture', 'address', 'phone', 'image_path', 'open_time', 'price'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
