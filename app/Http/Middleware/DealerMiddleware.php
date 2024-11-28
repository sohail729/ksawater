<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DealerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'dealer') {
                return $next($request);
            }
        }
        abort(403, 'Unauthorized. Please login as dealer!');
    }
}
