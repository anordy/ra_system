<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index($id){
        return view('auth.passwords.change',['id'=>$id]);
    }

    public function updatePassword(Request $request){
        
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ],[
            'password_confirmation.confirmed'=>'password and password confimation must match'
        ]);

        $user = User::find(Crypt::decrypt($request->user_id));
        $user->is_first_login = false;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('login')->with('success','Your password changed successfully, Now you can login with the new password you provided');
    }
}