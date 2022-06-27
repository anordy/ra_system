<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(){
        return View('business.registrations.index');
    }

    public function show($businessId){
        $business = Business::findOrFail($businessId);
        return view('business.registrations.show', compact('business'));
    }
}
