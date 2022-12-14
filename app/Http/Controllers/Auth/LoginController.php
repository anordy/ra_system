<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    protected $maxAttempts = 1;
    protected $decayMinutes = 10;


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

        $lockedOut = $this->hasTooManyLoginAttempts($request);
        if ($lockedOut) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }


        if (Auth::once($request->only('email', 'password'))) {
            Auth::logoutOtherDevices(request('password'));
            $user = auth()->user();
            if ($user->status == 0) {
                return redirect()->back()->withErrors([
                    "Your account is deactivated, Kindly check with your admin"
                ]);
            }

            if ($user->is_first_login == true) {
//                todo: why double encrypt?
                $id = Crypt::encrypt(Crypt::encrypt($user->id));
                session()->forget("token_id");
                session()->forget("user_id");
                session()->forget("email");
                session()->forget("password");
                return redirect()->route('password.change', $id);
            }

//            todo: login will always be true
            if ($user->otp == null && $user->is_first_login == false) {
                $token = UserOtp::create([
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'used' => false
                ]);
            } else {
                $token = $user->otp; // todo: if $user->is_first_login were to be true, this would be null
                $token->code = $token->generateCode(); // todo: hash OTP
                $token->updated_at = Carbon::now()->toDateTimeString();
                $token->save();
            }

            if ($token->sendCode()) {
//                todo: do not store/encrypt password - create a middleware
                session()->put("token_id", encrypt($token->id));
                session()->put("user_id", encrypt($user->id));
                session()->put("email", encrypt($request->get('email')));
                session()->put("password", encrypt($request->get('password'))); // todo: storing/encrypting password is not allowed security
                return redirect()->route('twoFactorAuth.index');
            }


            $token->delete();
            return redirect('/login')->withErrors([
                "Unable to send verification code"
            ]);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
