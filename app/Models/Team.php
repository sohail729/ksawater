<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Team extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'team';
    protected $guard = 'team';
    protected $guarded = [];

    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }
}
