<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessDirector;
use App\Models\BusinessShare;
use App\Models\BusinessShareholder;
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
        $directors = BusinessDirector::where('business_id', $business->id)->get() ?? [];
        $shareholders = BusinessShareholder::where('business_id', $business->id)->get() ?? [];
        $shares = BusinessShare::where('business_id', $business->id)->get() ?? [];
        return view('business.registrations.show', compact('business', 'directors', 'shareholders', 'shares'));
    }

    public function approval($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $business = Business::findOrFail(decrypt($businessId));
        return view('business.registrations.approval', compact('business'));
    } 

    public function approval_progress($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $business = Business::findOrFail(decrypt($businessId));
        return view('business.registrations.approval_progress', compact('business'));
    }
}
