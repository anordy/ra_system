<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function redirectTo(){
        return redirect()->route('home');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha'
        ]);
    }


    public function login(Request $request)
    {

        $this->validateLogin($request);
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        if ($user = app('auth')->getProvider()->retrieveByCredentials($request->only('email', 'password'))) {

            if ($user->otp == null) {
                $token = UserOtp::create([
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                    'used' => false
                ]);
            } else {
                $token = $user->otp;
                $token->code = $token->generateCode();
                $token->save();
            }


            if ($token->sendCode()) {
                session()->put("token_id", encrypt($token->id));
                session()->put("user_id", encrypt($user->id));
                session()->put("email", encrypt($request->get('email')));
                session()->put("password", encrypt($request->get('password')));
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
