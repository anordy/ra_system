<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Support\Facades\Gate;

class RegistrationController extends Controller
{
    public function index()
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        return View('business.registrations.index');
    }

    public function show($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $business = Business::findOrFail(decrypt($businessId));
        return view('business.registrations.show', compact('business'));
    }

    public function approval($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $business = Business::findOrFail($businessId);
        return view('business.registrations.approval', compact('business'));
    }
}
