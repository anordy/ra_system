<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WardController extends Controller
{
    //
    public function index()
    {
        if (!Gate::allows('setting-ward-view')) {
            abort(403);
        }

        return view('settings.ward');
    }
}
