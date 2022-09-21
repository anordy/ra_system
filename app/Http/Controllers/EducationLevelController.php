<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EducationLevelController extends Controller
{
    public function index(){
        if (!Gate::allows('setting-education-level-view')) {
            abort(403);
        }

        return view('settings.education_level');
    }
}
