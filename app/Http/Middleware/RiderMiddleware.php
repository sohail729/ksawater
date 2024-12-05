<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RiderMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('team')->check()) {
            if (auth('team')->user()->type == 'rider') {
                return $next($request);
            }
        }
        return response()->json([
            'status'        =>  401,
            'message'       =>  "401 Unauthorized.",
            'response'      =>  [
                'data'      => null,
                'errors'    => []
            ]
        ], 401);
    }
}
