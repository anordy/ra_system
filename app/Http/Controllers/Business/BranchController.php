<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
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
//        todo: select required columns to improve performance - (suggestion)
        $location = BusinessLocation::with('business')->findOrFail(decrypt($locationId));
        $business = $location->business;
        return view('business.branches.show', compact('location', 'business'));
    }
}
