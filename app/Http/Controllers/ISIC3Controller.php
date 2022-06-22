<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ISIC3Controller extends Controller
{
    public function index(){
        return view('settings.isic3');
    }
}
