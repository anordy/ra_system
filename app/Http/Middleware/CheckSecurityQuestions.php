<?php

namespace App\Http\Middleware;

use App\Models\SystemSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSecurityQuestions
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
        // Check if session already exist and continue
        if (Session::has('user_sec_qns')){
            return $next($request);
        }

        $setting = SystemSetting::where('code', SystemSetting::ENABLE_OTP_ALTERNATIVE)->first();

        if ($setting && $setting->value) {

            // Check if user has security questions configured
            $taxpayer = Auth::user();

            if ($taxpayer->userAnswers()->count() < 3) {
                // If not redirect to security questions page setup with a flash message
                Session::flash('error', 'Please setup your security questions before continuing to use your account.');
                return redirect()->route('account.pre-security-questions');
            } else {
                Session::put('user_sec_qns', $taxpayer->id);
            }

        }

        return $next($request);
    }
}
