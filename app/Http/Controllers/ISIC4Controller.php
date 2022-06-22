<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ISIC4Controller extends Controller
{
    public function index(){
        return view('settings.isic4');
    }
}
