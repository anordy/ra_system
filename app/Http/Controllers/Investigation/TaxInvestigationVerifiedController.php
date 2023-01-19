<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxInvestigationVerifiedController extends Controller
{
    public function index()
    {
        return view('investigation.verified.index');
    }

    public function show($id)
    {
        $investigation = TaxInvestigation::with('assessment', 'officers')->findOrFail(decrypt($id));
        $viewRender = "";
        return view('investigation.approval.preview', compact('investigation', 'viewRender'));
    }
}
