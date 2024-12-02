<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryLogs extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function rider()
    {
        return $this->belongsTo(Team::class)->where('type', 'rider');
    }

}
