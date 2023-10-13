<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class VerifyHostMiddleware
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
        $host = $request->header('Host');
        $hostDomain = parse_url($host, PHP_URL_HOST);

        $currentUrl = $request->url();
        $currentDomain = parse_url($currentUrl, PHP_URL_HOST);

        if (!$host || $hostDomain){
            return response('Malformed Request: Invalid Host', 403);
        }

        if ($hostDomain !== $currentDomain){
            return response('Malformed Request: Invalid Host', 403);
        }

        return $next($request);
    }
}
