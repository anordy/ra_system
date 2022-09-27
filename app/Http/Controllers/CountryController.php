<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CountryController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-country-view')) {
            abort(403);
        }

        return view('settings.country');
    }
}
