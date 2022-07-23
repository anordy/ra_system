<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(){
        return view('business.branches.index');
    }

    public function show($locationId){
        $location = BusinessLocation::with('business')->find(decrypt($locationId));
        $business = $location->business;
        return view('business.branches.show', compact('location', 'business'));
    }
}
