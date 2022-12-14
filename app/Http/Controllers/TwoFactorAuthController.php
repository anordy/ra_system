<?php

namespace App\Http\Controllers;

use App\Models\UserOtp;
use Carbon\Carbon;
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


// todo: check expiry separately
        $find = UserOtp::where('user_id', $userId)
            ->where('id', $tokenId)
            ->where('code', $code)
            ->where('updated_at', '>=', now()->subMinutes(13))
            ->first();


        if (!is_null($find)) {
//            todo: remove only after successful login
            session()->remove('user_id');
            session()->remove('token_id');
            session()->remove('email');
            session()->remove('password');

//            todo: if user status is not 1, it will return credentials do not match, check user status on login
            if (Auth::guard()->attempt(['email' => $email, 'password' => $password, 'status' => 1])) {
                $request->session()->regenerate();
                session()->put('user_2fa', encrypt(config('app.key')));
                return redirect()->route('home');
            }
//            todo: this returns user back to otp page, but you have already cleared the session
            return redirect()->back()->withErrors(['error' => 'These credentials do not match our records.']);
        }

//        todo: the message might mislead, it still says wrong code even when the problem is expiry
//        todo: in case code expires, how does user proceeds?
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
        $token->code = $token->generateCode(); //  todo: hash token
        $token->updated_at = Carbon::now()->toDateTimeString();
        $token->save();
//        todo: sendCode might return false, need to check
        $token->sendCode();
        
        Session::flash('success', 'Token resend successfully. Check your email/sms');

        return back();
    }
}
