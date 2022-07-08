<?php

namespace App\Http\Controllers;

use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TwoFactorAuthController extends Controller
{

    public function index()
    {
        $tokenId = session()->get('token_id');
        $userId = session()->get('user_id');

        if ($tokenId == null && $userId == null) {
            return redirect()->route('login');
        }
        return view('layouts.otp_confirm');
    }

    public function confirm(Request $request)
    {
        $tokenId = decrypt(session()->get('token_id'));
        $userId = decrypt(session()->get('user_id'));
        $password = decrypt(session()->get('password'));
        $email = decrypt(session()->get('email'));

        if ($tokenId == null && $userId == null) {
            return redirect()->route('login')->withErrors(['error' => 'Please login again']);
        }

        $validator = Validator::make($request->all(), [
            'first' => 'required',
            'second' => 'required',
            'third' => 'required',
            'fourth' => 'required',
            'fifth' => 'required',
            'sixth' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['error' => 'Verification Code is invalid']);
        }

        $validated = $validator->validate();
        $code = join($validated);



        $find = UserOtp::where('user_id', $userId)
            ->where('id', $tokenId)
            ->where('code', $code)
            ->where('updated_at', '>=', now()->subMinutes(13))
            ->first();

        if (!is_null($find)) {
            session()->remove('user_id');
            session()->remove('token_id');
            session()->remove('email');
            session()->remove('password');

            if (Auth::guard()->attempt(['email' => $email, 'password' => $password, 'status' => 1])) {
                $request->session()->regenerate();
                session()->put('user_2fa', encrypt(config('app.key')));
                return redirect()->route('home');
            }
            return redirect()->back()->withErrors(['error' => 'These credentials do not match our records.']);
        }

        return redirect()->back()->withErrors(['error' => 'You entered wrong code']);
    }

    public function resend()
    {
        $tokenId = decrypt(session()->get('token_id'));
        $userId = decrypt(session()->get('user_id'));

        if ($tokenId == null && $userId == null) {
            return redirect()->route('login')->withErrors(['error' => 'Please login again']);
        }

        $token = UserOtp::find($tokenId);
        $token->sendCode();
        
        Session::flash('success', 'Token resend successfully. Check your email/sms');

        return back();
    }
}
