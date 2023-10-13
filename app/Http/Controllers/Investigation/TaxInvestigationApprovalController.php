<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Investigation\TaxInvestigation;
use Illuminate\Support\Facades\Gate;

class TaxInvestigationApprovalController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-investigation-approval-view')) {
            abort(403);
        }
        return view('investigation.approval.index');
    }

    public function edit($id)
    {
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        $investigation = TaxInvestigation::findOrFail(decrypt($id));
        return view('investigation.approval.approval', compact('investigation'));
    }

    public function show($id)
    {
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        $investigation = TaxInvestigation::with('assessment', 'officers')->findOrFail(decrypt($id));
        return view('investigation.approval.preview', compact('investigation'));
    }
}
