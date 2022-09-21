<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BankController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-bank-view')) {
            abort(403);
        }

        return view('settings.bank');
    }
}
