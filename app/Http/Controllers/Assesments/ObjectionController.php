<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ObjectionController extends Controller
{
    public function index()
    {
        if (!Gate::allows('dispute-objection-view')) {
            abort(403);
        }

         return view('assesments.objection.index');
    }

    public function edit()
    {
        return view('assesments.objection.edit');
    }
}
