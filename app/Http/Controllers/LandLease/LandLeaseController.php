<?php

namespace App\Http\Controllers\LandLease;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandLeaseController extends Controller
{
    //

    public function index()
    {
        return view('land-lease.land-lease-list');
    }

    public function view($id)
    {
        return view('land-lease.view-land-lease', compact('id'));
    }

}
