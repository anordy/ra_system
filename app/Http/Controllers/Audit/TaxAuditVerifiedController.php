<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\TaxAudit\TaxAudit;

class TaxAuditVerifiedController extends Controller
{
    public function index()
    {
        return view('audit.verified.index');
    }

    public function show($id)
    {
        $audit = TaxAudit::with('assessment', 'officers')->findOrFail(decrypt($id));
        return view('audit.preview', compact('audit'));
    }
}
