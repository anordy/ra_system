<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;

class ReliefRegistrationController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('relief-registration-view')) {
            abort(403);
        }
        return view('relief.registration.index');
    }
}
