<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ISIC1Controller extends Controller
{
    public function index(){
        if (!Gate::allows('setting-isic-level-one-view')) {
            abort(403);
        }
        
        return view('settings.isic1');
    }
}
