<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;

class ReliefMinistriestController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('relief-ministries-view')) {
            abort(403);
        }
        return view('relief.ministries.index');
    }
}
