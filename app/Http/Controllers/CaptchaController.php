<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function reload(){
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
}
