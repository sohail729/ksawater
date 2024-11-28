<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function detail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment_logs()
    {
        return $this->hasMany(PaymentLogs::class);
    }

}
