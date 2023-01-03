<?php

namespace App\Http\Controllers;

use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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


        $user = auth()->user();

        $otp = UserOtp::where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->first();

        if ($otp == null) {
            return redirect()->back()->withErrors('Token supplied does not exits');
        }

        if (!Hash::check($code, $otp->code)) {
            return redirect()->back()->withErrors('Invalid token supplied');
        }

        if ($otp->isUsed()) {
            return redirect()->back()->withErrors('Token has already used');
        }

        if ($otp->isExpired()) {
            return redirect()->back()->withErrors('Token has already expired');
        }

        $otp->used = true;
        $otp->save();
        
        if ($user->is_first_login == true || $user->is_password_expired == 1) {
            return redirect()->route('password.change');
        } else {
            session()->put('user_2fa', $user->id);
            return redirect()->route('home');
        }
    }

    public function resend()
    {
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

        Session::flash('success', 'Token resend successfully. Check your email/sms');

        return back();
    }

    public function kill(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login');
    }
}
