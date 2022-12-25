<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers, VerificationTrait;

    protected $maxAttempts = 3;
    protected $decayMinutes = 2;


    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function getPayloadColumns(): array
    {
        return ['id', 'email', 'phone', 'password', 'status'];
    }

    public function getPayloadTable(): string
    {
        return 'users';
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
            // 'captcha' => 'required|captcha'
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

        if($user == null){
            return $this->sendFailedLoginResponse($request);
        }

        $attempts = $this->hasTooManyLoginAttempts($user);
        if ($attempts) {
            return $this->sendLockoutResponse($user);
        }

        if ($user->status == 0) {
            throw ValidationException::withMessages([
                $this->username() =>  "Your account is locked, Please contact your admin to unlock your account",
            ]);
        }

        if (!$this->verify($user->id)){
            throw ValidationException::withMessages([
                $this->username() =>  "Your account could not be verified, please contact system administrator.",
            ]);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            Auth::logoutOtherDevices(request('password'));
            $this->clearLoginAttempts($request);
            $user = auth()->user();

            if ($user->is_first_login == false) {
                $token = $user->otp;

                if ($token == null) {
                    $token = UserOtp::create([
                        'user_id' => $user->id,
                        'user_type' => get_class($user),
                        'used' => false
                    ]);
                } else {
                    $token->used = false;
                    $token->code = $token->generateCode();
                    $token->updated_at = Carbon::now()->toDateTimeString();
                    $token->save();
                }

                $token->sendCode();

                return redirect()->route('twoFactorAuth.index');
            }else{
                return redirect()->route('password.change');
            }
        } else {
            $this->incrementLoginAttempts($user, $request);
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function hasTooManyLoginAttempts($user)
    {
        if ($user->auth_attempt >= $this->maxAttempts) {
            return true;
        }
    }

    protected function incrementLoginAttempts($user, $request)
    {
        $increment = $user->auth_attempt + 1;
        $user->auth_attempt = +$increment;
        $user->save();
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    protected function sendLockoutResponse($user)
    {
        $user->status = 0;
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
}
