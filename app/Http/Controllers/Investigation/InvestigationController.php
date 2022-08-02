<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class InvestigationController extends Controller
{
    public function index(Request $request)
    {
        return view('investigation.index');
    }
}
