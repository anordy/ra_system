<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
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
    public function business()
    {
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }
        

        return view('audit.approval.businesses');
    }
    public function showBusiness($id)
    {
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }

        $location = BusinessLocation::whereHas('taxVerifications', function ($query) {
            $query->with('riskIndicators'); // Eager load risk indicators with tax verification
          })->findOrFail(decrypt($id));
          
          $taxReturns = $location->taxVerifications->map(function ($verification) {
            return $verification->taxReturn;
          });
          
          return view('audit.business.show', compact('location', 'taxReturns'));
        
    }

    public function edit($id)
    {

        $audit = TaxAudit::findOrFail(decrypt($id));
        return view('audit.approval.approval', compact('audit'));
    }

    public function show($id)
    {
        $audit = TaxAudit::with('assessment', 'officers', 'business')->findOrFail(decrypt($id));
        return view('audit.preview', compact('audit'));
    }
}
