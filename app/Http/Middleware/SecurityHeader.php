<?php

namespace App\Http\Middleware;

use Closure;

class SecurityHeader
{

    private $unwantedHeaders = ['X-Powered-By', 'Server'];

    /**
     * @param $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaders);
        $response = $next($request);

        if (!app()->environment('testing')) {
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->headers->set('Access-Control-Allow-Origin', 'localhost,uat.ubx.co.tz');
            $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type,Authorization');
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            $response->headers->set('Permissions-Policy', 'autoplay=(self), camera=(), encrypted-media=(self), fullscreen=(), geolocation=(self), gyroscope=(self), magnetometer=(), microphone=(), midi=(), payment=(), sync-xhr=(self), usb=()');
            $response->headers->set('Connection', 'off');
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
            $response->headers->set('Clear-Site-Data', 'cache, cookies');
            $response->headers->set('Cross-Origin-Embedder-Policy', 'same-origin');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
            $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

            if ($this->checkRoute($request->route() ? $request->route()->getName() : null)) {
                $response->headers->set('Content-Security-Policy', "frame-ancestors 'self'; form-action 'self'; default-src 'self'; style-src fonts.googleapis.com 'self' 'nonce-custom_style'; script-src 'self' 'nonce-custom_script'; font-src 'self' fonts.gstatic.com; img-src 'self' data:");
            }
        }


        return $response;
    }

    /**
     * @param $headers
     */
    private function removeUnwantedHeaders($headers): void
    {
        foreach ($headers as $header) {
            header_remove($header);
        }
    }

    private function checkRoute($routeName) {
        return in_array($routeName, [
            null,
            '/',
            'password.request',
            'password.reset'
        ]);
    }
}
