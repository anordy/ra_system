<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;

class PetroleumReturnController extends Controller
{
    public function index()
    {
        return view('returns.petroleum.index');
    }
}
