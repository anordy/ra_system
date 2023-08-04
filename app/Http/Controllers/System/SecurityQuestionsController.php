<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityQuestionsController extends Controller
{
    public function index(){
        return view('system.security-questions.index');
    }
}
