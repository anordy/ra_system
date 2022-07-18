<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WardController extends Controller
{
    //
    public function index()
    {
        return view('settings.ward');
    }
}
