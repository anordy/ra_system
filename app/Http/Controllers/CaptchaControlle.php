<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaControlle extends Controller
{
    public function reload(){
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
}
