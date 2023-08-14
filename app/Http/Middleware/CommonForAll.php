<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommonForAll
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log::info('IP: '.request()->ip().', URL: '.request()->url().', ROUTE: '.Route::currentRouteName().', DATE: '.date("Y-m-d h:i:s"));
        Log::info('IP: '.request()->ip().', URL: '.request()->url().', DATE: '.date("Y-m-d h:i:s"));
        return $next($request);
    }
}
