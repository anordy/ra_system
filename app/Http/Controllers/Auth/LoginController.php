<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        if (Auth::once($request->only('email', 'password'))) {
            $user = auth()->user();
            if ($user->status == 0) {
                return redirect()->back()->withErrors([
                    "Your account is deactivated, Kindly check with your admin"
                ]);
            }

            if ($user->is_first_login == true ) {
                $id = Crypt::encrypt(Crypt::encrypt($user->id));
                session()->forget("token_id");
                session()->forget("user_id");
                session()->forget("email");
                session()->forget("password");
                return redirect()->route('password.change',$id);
            }

            if ($user->otp == null && $user->is_first_login == false) {
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
