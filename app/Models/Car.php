<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'clicks',
        'features',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    public function brand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function model()
    {
        return $this->belongsTo(CarModel::class)->withDefault(function ($model) {
            $model->id = null;
            $model->name = null;
        });
    }

    public function featured()
    {
        return $this->hasMany(FeaturedCar::class);
    }

    public function getIsFeaturedAttribute()
    {
        return $this->featured->isNotEmpty();
    }

    public static function getAllCarsCount($dealerId)
    {
        return self::where('user_id', $dealerId)->count();
    }

    // Function to get the count of available cars
    public static function getAvailableCarsCount($dealerId)
    {
        return self::where('user_id', $dealerId)->where('status', '1')->count();
    }

    // Function to get the count of rented cars
    public static function getRentedCarsCount($dealerId)
    {
        return self::where('user_id', $dealerId)->where('status', '2')->count();
    }

}
