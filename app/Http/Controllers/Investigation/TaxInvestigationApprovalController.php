<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;

class TaxInvestigationApprovalController extends Controller
{
    public function index()
    {
        return view('investigation.approval.index');
    }

    public function edit($id)
    {

        $investigation = TaxInvestigation::with('assessment', 'officers')->find(decrypt($id));
        $viewRender = "";
        return view('investigation.approval.approval', compact('investigation', 'viewRender'));
    }

    public function show($id)
    {

        $investigation = TaxInvestigation::with('assessment', 'officers')->find(decrypt($id));
        return view('investigation.approval.preview', compact('investigation'));
    }
}
