<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ISIC2Controller extends Controller
{
    public function index(){
        return view('settings.isic2');
    }
}
