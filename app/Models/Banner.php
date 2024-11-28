<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // public function getImageAttribute($value)
    // {
    //     return url('storage/uploads/banners/' . $value);
    // }
}
