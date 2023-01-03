<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StreetController extends Controller
{
    public function index(){
        if (!Gate::allows('setting-street-view')) {
            abort(403);
        }

        return view('settings.street');
    }
}
