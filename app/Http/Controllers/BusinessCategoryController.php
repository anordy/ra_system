<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BusinessCategoryController extends Controller
{
    public function index(){

        if (!Gate::allows('setting-business-category-view')) {
            abort(403);
        }
        
        return view('settings.business-cat');
    }
}
