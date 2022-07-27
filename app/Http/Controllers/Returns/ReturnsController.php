<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;

class ReturnsController extends Controller
{
    public function index(){
        return view('settings.returns');
    }


}
