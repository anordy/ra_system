<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ISIC3Controller extends Controller
{
    public function index(){
        if (!Gate::allows('setting-isic-level-three-view')) {
            abort(403);
        }

        return view('settings.isic3');
    }
}
