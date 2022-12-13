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
        return view('layouts.otp_confirm');
    }

    public function confirm(Request $request)
    {

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


        $user  = auth()->user();

        $otp = UserOtp::where('user_id', $user->id)
            ->where('code', $code)
            ->first();

        if ($otp == null) {
            return redirect()->back()->withErrors('Not a valid code kindly resend to get new code');
        }
        if ($otp->isUsed()) {
            return redirect()->back()->withErrors('Your token has already used');
        }
        if ($otp->isExpired()) {
            return redirect()->back()->withErrors('Your token has already expired');
        }
        $otp->used = true;
        $otp->save();

        session()->put('user_2fa', $user->id);
        return redirect()->route('home');
    }

    public function resend()
    {

        $user = auth()->user();
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

        Session::flash('success', 'Token resend successfully. Check your email/sms');

        return back();
    }

    public function kill()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
