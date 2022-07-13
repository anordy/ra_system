<?php

namespace App\Http\Controllers;

class WithholdingAgentController extends Controller
{
    public function index()
    {
        return view('withholding-agent.index');
    }

    public function view()
    {
        return view('withholding-agent.view');
    }

    public function registration()
    {
        return view('withholding-agent.registration');
    }


}
