<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBrand extends Model
{
    protected $fillable = ['name', 'is_top', 'description', 'logo'];
    public $timestamps = false;

    public function models()
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }
}
