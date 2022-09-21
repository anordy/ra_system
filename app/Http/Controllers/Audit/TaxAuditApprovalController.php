<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\TaxAudit\TaxAudit;

class TaxAuditApprovalController extends Controller
{
    public function index()
    {
        return view('audit.approval.index');
    }

    public function edit($id){

        $audit = TaxAudit::with('assessment', 'officers')->find(decrypt($id));
        // return $audit->taxAuditLocationNames();
        return view('audit.approval.approval', compact('audit'));

    }

    public function show($id)
    {
        $audit = TaxAudit::with('assessment', 'officers')->find(decrypt($id));
        return view('audit.preview', compact('audit'));
    }
 
}
