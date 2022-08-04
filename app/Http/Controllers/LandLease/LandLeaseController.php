<?php

namespace App\Http\Controllers\LandLease;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

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

    public function getAgreementDocument($path)
    {
        return Storage::disk('local-admin')->response(decrypt($path));
    }

    public function generateReport()
    {
        return view('land-lease.generate-report');
    }

    // public function reportPreview(Request $request)
    // {
       
    //     // $query = $request->query;
    //     // $landLeases = DB::select($query);
    //     // return view("land-lease.report-preview", compact('landLeases'));
    //     dd($request->all());
    // }
  
}
