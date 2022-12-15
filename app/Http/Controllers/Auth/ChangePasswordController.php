<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{

    public function index()
    {
        return view('auth.passwords.change');
    }

    public function updatePassword(Request $request)
    {

//        todo: security threat, better put this in middleware
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ], [
            'password_confirmation.confirmed' => 'password and password confimation must match'
        ]);

        $user = auth()->user();

        $user->is_first_login = false;
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Your password changed successfully, Now you can login with the new password you provided');
    }
}
