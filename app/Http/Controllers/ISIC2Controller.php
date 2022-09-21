<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ISIC2Controller extends Controller
{
    public function index(){
        if (!Gate::allows('setting-isic-level-two-view')) {
            abort(403);
        }

        return view('settings.isic2');
    }
}
