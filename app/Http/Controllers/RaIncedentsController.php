<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RaIncedentsController extends Controller
{
    public function index()
    {
        return view('incedent.index');
    }

    public function create()
    {
        return view('incedent.crate');
    }
    public function show($id)
    {
        return view('incedent.show');
    }
}
