<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // dd(auth('team')->check());
        if (auth('team')->check()) {
            if (auth('team')->user()->type == 'admin') {
                return $next($request);
                // return redirect()->route('admin.dashboard');
            }
        }
        return redirect()->route('admin.cpanelShowLogin');
    }
}
