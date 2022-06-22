<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessCategoryController extends Controller
{
    public function index(){
        return view('settings.business-cat');
    }
}
