<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class VerifyRefererMiddleware
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
        $referer = $request->header('Referer');
        $currentUrl = $request->url();
        $currentDomain = parse_url($currentUrl, PHP_URL_HOST);

        if (!$referer){
            return $next($request);
        }

        $refererDomain = parse_url($referer, PHP_URL_HOST);

        // 1. Check if domain does not match
        if ($refererDomain){
            if ($refererDomain !== $currentDomain){
                return response('Malformed Request: Invalid Base', 403);
            }
        }


        $refererUrl = parse_url($referer);
        $path = str_replace(config('constants.context_path'), '', $refererUrl['path']);

        // 2. Check if the request matches defined routes
        $routes = Route::getRoutes();

        try {
            if (!$routes->match(app('request')->create($path))){
                return response('Malformed Request: Invalid Path', 403);
            }
        } catch (\Exception $exception){
            logger("Invalid referer URL detected");
            logger($referer);
            logger($refererUrl);
            logger($exception);
            return response('Malformed Request: Invalid URL', 403);
        }

        return $next($request);
    }
}
