<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AgentsController extends Controller
{

    public function index(){

        return view('mvr.agent-index');
    }

    public function create(){

        return view('mvr.agent-create');
    }

}
