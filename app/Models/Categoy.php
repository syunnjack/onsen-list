<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoy extends Model
{
    protected $fillable = ['name', 'slug'];

    public function onsens()
    {
        return $this->belongsToMany(Onsen::class);
    }
}
