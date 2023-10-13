<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;

class AgentsController extends Controller
{

    public function index(){
        if (!Gate::allows('motor-vehicle-transport-agent')) {
            abort(403);
        }
        return view('mvr.agent-index');
    }

    public function create(){
        if (!Gate::allows('motor-vehicle-transport-agent-create')) {
            abort(403);
        }
        return view('mvr.agent-create');
    }

}
