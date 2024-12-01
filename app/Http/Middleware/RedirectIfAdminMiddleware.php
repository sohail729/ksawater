<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('team')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return $next($request);
    }
}
