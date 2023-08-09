<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function show(){
        return view('account.show');
    }

    public function securityQuestions(){
        return view('account.security-questions');
    }

    public function preSecurityQuestions(){
        return view('account.pre-security-questions');
    }
}
