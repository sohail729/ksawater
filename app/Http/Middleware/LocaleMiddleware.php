<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $host = $request->getHost();
        if ($host == 'now2rent.nl') {
            $defualt = 'nl';
        } else {
            $defualt = 'en';
        }

        app()->setlocale(session()->get('lang') ?? $defualt);
        return $next($request);
    }
}
