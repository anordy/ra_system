<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ReliefRegistrationController extends Controller
{
    public function index(Request $request)
    {
        return view('relief.registration.index');
    }
}
