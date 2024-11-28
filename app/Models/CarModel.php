<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function brand()
    {
        return $this->belongsTo(CarBrand::class);
    }
}
