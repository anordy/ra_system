<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{

    public function index()
    {
        return view('auth.passwords.change' , ['expired_message' => Auth::user()->pass_expired_on <= Carbon::now() ? 'Your password has expired. Please reset!. Note that you cannot use any of your previous passwords when creating a new password!' : '']);
    }

    public function updatePassword(Request $request)
    {

//        todo: security threat, better put this in middleware
        $request->validate([
            'password' => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&.]/',
            'password_confirmation' => 'required',
        ], [
            'password_confirmation.confirmed' => 'password and password confimation must match'
        ]);

        $user = auth()->user();

        //Checking Password from history
        if ($user->passwordExistInHistory($request->password)) {
            throw ValidationException::withMessages([
                $this->username() => 'Sorry, but the password you have entered has already been used. Please choose a new password that you have not used before.',
            ]);
        }

        $passwordExpirationDuration = SystemSetting::where('code', SystemSetting::PASSWORD_EXPIRATION_DURATION)->where('is_approved', 1)->value('value');

        $user->is_first_login = false;
        $user->password = Hash::make($request->password);
        $user->pass_expired_on = Carbon::now()->addDay($passwordExpirationDuration);
        $user->save();
        
        //Save password to history
        $passwordHistory = new PasswordHistory();
        $passwordHistory->user_id = $user->id;
        $passwordHistory->user_type = get_class($user);
        $passwordHistory->password_entry = $user->password;
        $passwordHistory->created_at = Carbon::now();
        $passwordHistory->save();

        Auth::logout();
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Your password changed successfully, Login with your new password');
    }

    public function username()
    {
        return 'password';
    }
}
