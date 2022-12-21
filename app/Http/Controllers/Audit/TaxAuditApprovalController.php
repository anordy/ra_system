<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Support\Facades\Gate;

class TaxAuditApprovalController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }
        

        return view('audit.approval.index');
    }

    public function edit($id)
    {

        $audit = TaxAudit::find(decrypt($id));
        return view('audit.approval.approval', compact('audit'));
    }

    public function show($id)
    {
        $audit = TaxAudit::with('assessment', 'officers', 'business')->find(decrypt($id));
        return view('audit.preview', compact('audit'));
    }
}
