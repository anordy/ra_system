<?php

namespace App\Http\Controllers\Assesments;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class WaiverObjectionController extends Controller
{
    public function index()
    {
        if (!Gate::allows('dispute-waiver-objection-view')) {
            abort(403);
        }
    
        return view('assesments.waiverobjection.index');
    }
}
