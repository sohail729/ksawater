<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'admin') {
                return $next($request);
            }
        }
        return redirect()->route('cpanelShowLogin');
    }
}
