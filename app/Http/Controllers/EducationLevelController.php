<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EducationLevelController extends Controller
{
    public function index(){
        return view('settings.education_level');
    }
}
