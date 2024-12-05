<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('admin')->check() && auth('admin')->user()->type == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return $next($request);
    }
}
