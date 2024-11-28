<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    public function dealer()
    {
        return $this->belongsTo(User::class);
    }
}
