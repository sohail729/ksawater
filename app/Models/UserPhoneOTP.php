<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhoneOTP extends Model
{
    protected $table = 'user_phone_otp';
    protected $guarded = [];
    public $timestamps = false;
}
