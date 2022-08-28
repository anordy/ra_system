<?php

namespace App\Http\Controllers\Business;

use App\Models\BusinessLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class BranchController extends Controller
{
    public function index(){
        if (!Gate::allows('business-branches-view')) {
            abort(403);
        }
        return view('business.branches.index');
    }

    public function show($locationId){
        if (!Gate::allows('business-branches-view')) {
            abort(403);
        }
        $location = BusinessLocation::with('business')->find(decrypt($locationId));
        $business = $location->business;
        return view('business.branches.show', compact('location', 'business'));
    }
}
