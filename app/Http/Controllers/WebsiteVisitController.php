<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsiteVisit;

class WebsiteVisitController extends Controller
{
    public function store(Request $request)
    {
        WebsiteVisit::create([
            'ip_address' => $request->ip(),
            'page_visited' => $request->url(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
        ]);

        return response()->json(['message' => 'Visit recorded successfully']);
    }
}

