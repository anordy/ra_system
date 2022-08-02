<?php


namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        return view('verification.index');
    }
}
