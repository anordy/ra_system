<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    protected $redirectTo = RouteServiceProvider::ROOT_URL;

    /**
     * Overriden Method: Reset the given user's password.
     */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);
        $user->setRememberToken(Str::random(60));
        $user->save();
        event(new PasswordReset($user));
        return redirect()->route('login')->with('success', 'Your Password has been reset! Please login with your new password');
    }

    /**
     * Overriden Method: Get the response for a failed password reset.
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        if ($request->wantsJson()) {
            throw ValidationException::withMessages([
                'email' => [trans($response)],
            ]);
        }

        return redirect()->back()->with('error', trans($response));
    }



}
