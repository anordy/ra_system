<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaxTypeController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-tax-type-view')) {
            abort(403);
        }

        return view('settings.taxtype');
    }
}
