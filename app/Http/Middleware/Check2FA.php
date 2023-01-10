<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Check2FA
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

        if (!Session::has('user_2fa')) {
            return redirect()->route('twoFactorAuth.index');
        }

        $user = auth()->id();

        $token = Session::get('user_2fa');
        if ($token != $user) {
            Auth::logout();
            $request->session()->flush();
            return redirect()->route('login')->withErrors('Suspicious Login Attempt');
        }

        return $next($request);
    }
}
