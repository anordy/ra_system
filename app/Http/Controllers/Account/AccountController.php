<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function __construct()
    {
    }
    
    public function show(){
        return view('account.show');
    }
}
