<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
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
    protected $redirectTo = '/login';

    /**
     * Overriden Method: Reset the given user's password.
     */
    protected function resetPassword($user, $password)
    {
        if ($user->passwordExistInHistory($password)) {
            throw ValidationException::withMessages([
                $this->username() => 'Sorry, but the password you have entered has already been used. Please choose a new password that you have not used before.',
            ]);
        }

        $passwordExpirationDuration = SystemSetting::where('code', SystemSetting::PASSWORD_EXPIRATION_DURATION)->where('is_approved', 1)->value('value');

        $this->setUserPassword($user, $password);
        $user->setRememberToken(Str::random(60));
        $user->pass_expired_on = Carbon::now()->addDay($passwordExpirationDuration);
        $user->save();

        //Save Hash password to history
        $passwordHistory = new PasswordHistory();
        $passwordHistory->user_id = $user->id;
        $passwordHistory->user_type = get_class($user);
        $passwordHistory->password_entry = $user->password;
        $passwordHistory->created_at = Carbon::now();
        $passwordHistory->save();

        event(new PasswordReset($user));
        return redirect('/')->with('success', 'Your Password has been reset! Please login with your new password');
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

    public function username()
    {
        return 'password';
    }

}
