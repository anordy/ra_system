<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyHostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (config('app.env') != 'local') {

            $allowedHosts = ['zanmalipo.go.tz', env('BASE_URL', 'manzidras.zanrevenue.org')];

            $host = $request->getHost();

            if (empty($host)) {
                return response('Malformed Request: Host Not Found.', 403);
            }

            if (!in_array($host, $allowedHosts)) {
                return response('Malformed Request: Invalid Host.', 403);
            }
        }

        return $next($request);
    }
}
