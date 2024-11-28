<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function getImageAttribute($value)
    // {
    //     return url('storage/uploads/banners/' . $value);
    // }
}
