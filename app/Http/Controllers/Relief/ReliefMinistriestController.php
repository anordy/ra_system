<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReliefMinistriestController extends Controller
{
    public function index(Request $request)
    {
        return view('relief.ministries.index');
    }
}
