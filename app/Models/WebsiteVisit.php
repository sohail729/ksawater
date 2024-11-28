<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteVisit extends Model
{
    protected $fillable = [
        'ip_address',
        'visit_timestamp',
        'page_visited',
        'user_agent',
        'referer',
        'visit_count',
    ];
}
