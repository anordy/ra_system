<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithholdingAgentController extends Controller
{
    public function index()
    {
        return view('withholding-agent.index');
    }

    public function registration()
    {
        return view('withholding-agent.registration');
    }


}
