<?php

namespace App\Http\Controllers;

use App\Models\RaIncedent;
use App\Models\RaIssue;
use Illuminate\Http\Request;

class RaIncedentsController extends Controller
{
    public function index()
    {
        return view('incedent.index');
    }

    public function create()
    {
        return view('incedent.create');
    }
    public function show($id)
    {
        $incedent = RaIncedent::findOrFail(decrypt($id));
        $leakages = RaIssue::where('ra_incident_id',$incedent->id)->get();
        return view('incedent.show',compact('incedent','leakages'));
    }
}
