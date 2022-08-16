<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;

class RegistrationController extends Controller
{
    public function index()
    {
        return View('business.registrations.index');
    }

    public function show($businessId)
    {
        $business = Business::findOrFail(decrypt($businessId));
        return view('business.registrations.show', compact('business'));
    }

    public function approval($businessId)
    {
        $business = Business::findOrFail($businessId);
        return view('business.registrations.approval', compact('business'));
    }
}
