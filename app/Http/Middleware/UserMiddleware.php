<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('api')->check()) {
            return $next($request);
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
