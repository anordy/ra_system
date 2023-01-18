<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\DualControl;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\UserOtp;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers, VerificationTrait;

    protected $maxAttempts;
    protected $decayMinutes;


    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ], [
            'captcha.required' => 'Please provide validation captcha.',
            'captcha.captcha' => 'You have provided invalid captcha. Please try again.'
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user =  $request->input($this->username());
        $user = User::where('email', $user)->first();

        if ($user == null) {
            return $this->sendFailedLoginResponse($request);
        }

        $attempts = $this->hasTooManyLoginAttempts($user);
        if ($attempts) {
            $message = 'We have detected multiple login attempts on your account. For security purposes, we have temporarily disabled your account. If you believe this is an error, please contact ZIDRAS support immediately.';
            $payload = [
                'email' => $request['email'],
                'message' => $message,
            ];
            event(new SendMail('too-many-login-attempts', $payload));
            return $this->sendLockoutResponse($user);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            Auth::logoutOtherDevices(request('password'));

            if ($user->status == 0) {
                Auth::logout();
                $request->session()->flush();
                throw ValidationException::withMessages([
                    $this->username() =>  "Your account is locked, Please contact your admin to unlock your account",
                ]);
            }

//            if (!$this->verify($user)) {
//                Auth::logout();
//                $request->session()->flush();
//                throw ValidationException::withMessages([
//                    $this->username() =>  "Your account could not be verified, please contact system administrator.",
//                ]);
//            }

            if ($user->is_approved == 0) {
                Auth::logout();
                $request->session()->flush();
                throw ValidationException::withMessages([
                    $this->username() =>  "Your account has not been approved, please contact system administrator.",
                ]);
            }

            $this->clearLoginAttempts($request);
            $user = auth()->user();

            $token = $user->otp;

            $code = UserOtp::generate();

            if ($token == null) {
                $token = UserOtp::create([
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'used' => false,
                    'code' => Hash::make($code)
                ]);
            } else {
                $token->used = false;
                $token->code = Hash::make($code);
                $token->updated_at = Carbon::now()->toDateTimeString();
                $token->save();
            }

            $token->sendCode($code);

            return redirect()->route('twoFactorAuth.index');
        } else {
            $this->incrementLoginAttempts($user, $request);
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function hasTooManyLoginAttempts($user)
    {
        $this->maxAttempts = SystemSetting::where('code', SystemSetting::MAXIMUM_NUMBER_OF_ATTEMPTS)->where('is_approved', DualControl::APPROVE)->value('value');
        if ($user->auth_attempt >= $this->maxAttempts) {
            return true;
        }

        return false;
    }

    protected function incrementLoginAttempts($user, $request)
    {
        $increment = $user->auth_attempt + 1;
        $user->auth_attempt = +$increment;
        $user->save();
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes() * 60 //pull configured value for decay minutes from system_settings table
        );
    }

    protected function sendLockoutResponse($user)
    {
        $user->status = 0;
        $user->auth_attempt = 0;
        $user->save();
        $message   = __('Your Account has been locked out because of too many Login attempts. Please contact your admin to unblock your account');

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }

    protected function clearLoginAttempts(Request $request)
    {
        $user = Auth::user();
        $user->auth_attempt = 0;
        $user->save();
        $this->limiter()->clear($this->throttleKey($request));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
