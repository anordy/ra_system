<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Support\Facades\Log;

class TaxAuditVerifiedController extends Controller
{
    public function index()
    {
        return view('audit.verified.index');
    }

    public function show($id)
    {
        try {
            $audit = TaxAudit::with('assessment', 'officers')->findOrFail(decrypt($id));
            return view('audit.preview', compact('audit'));
        } catch (\Exception $e) {
            report($e);
            Log::error($e);
            session()->flash('warning', 'The selected audit was not found. Please contact your administrator');
            return back();
        }
    }
}
