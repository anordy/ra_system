<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index(){
        if (!Gate::allows('setting-role-view')) {
            abort(403);
        }

        return view('settings.role');
    }
}
