<?php

namespace App\Http\Middleware;

use App\Models\WebsiteVisit;
use Closure;
use Illuminate\Http\Request;

class RecordWebsiteVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $visit = WebsiteVisit::where(['ip_address' => $request->ip(), 'page_visited' => $request->url()])->first();
        if (empty($visit)) {
            WebsiteVisit::create([
                'ip_address' => $request->ip(),
                'page_visited' => $request->url(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'visit_count' => 1,
            ]);
        } else {
            $visit->update(['visit_count' => $visit->visit_count + 1]);
        }
        return $next($request);
    }
}
