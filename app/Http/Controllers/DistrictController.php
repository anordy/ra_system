<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DistrictController extends Controller
{

    public function index()
    {
        if (!Gate::allows('setting-district-view')) {
            abort(403);
        }

        return view('settings.district');
    }
}
