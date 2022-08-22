<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;

class AgentsController extends Controller
{

    public function index(){

        return view('mvr.agent-index');
    }

    public function create(){

        return view('mvr.agent-create');
    }

}
