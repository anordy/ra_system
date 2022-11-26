<?php

namespace App\Http\Controllers\Finances;

use App\Enum\LeaseStatus;
use App\Enum\ReturnCategory;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Investigation\TaxInvestigation;
use App\Models\LandLeaseDebt;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FinanceController extends Controller
{
    //
    public function taxpayerLedgersList(){
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }
        return view('finance.view-taxpayer-ledgers');
    }

    public function taxpayerLedger($businessId)
    {
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }
        $business = Business::findOrFail(decrypt($businessId));
        
        // dd($business->businessLocationIDs());

        $businessTaxReturnDebts = TaxReturn::where('business_id', $business->id)
        ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
        ->where('payment_status', '!=', ReturnStatus::COMPLETE)
        ->with('installment')
        ->with('location')
        ->get()
        ->groupBy('location_id');

        
        $locations = $business->businessLocationIDs();

        $businessLandLeaseDebts = LandLeaseDebt::whereIn('business_location_id', $locations)
        ->where('status', LeaseStatus::PENDING)
        ->get()
        ->groupBy('business_location_id');

        $businessInvestigationDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get()
            ->groupBy('business_location_id');
        
        $businessAuditDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                $query->whereHas('taxAuditLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get()
            ->groupBy('business_location_id');

        $businessVerificateionDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
                                ->where('location_id', $locations)
                                ->get()
                                ->groupBy('location_id');

        return view('finance.taxpayer-ledger', compact('business', 'businessTaxReturnDebts', 'businessLandLeaseDebts', 'businessInvestigationDebts', 'businessAuditDebts', 'businessVerificateionDebts'));
    }
}
