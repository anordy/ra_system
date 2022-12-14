<?php

namespace App\Http\Controllers\Finances;

use App\Enum\LeaseStatus;
use App\Enum\ReturnCategory;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Investigation\TaxInvestigation;
use App\Models\LandLeaseDebt;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
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


        $unpaidBusinessTaxReturnDebts = TaxReturn::where('business_id', $business->id)
        ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
        ->where('payment_status', '!=', ReturnStatus::COMPLETE)
        ->with('installment')
        ->with('location')
        ->get()
        ->groupBy('location_id');

        $paidBusinessTaxReturnDebts = TaxReturn::where('business_id', $business->id)
        ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
        ->where('payment_status', ReturnStatus::COMPLETE)
        ->with('installment')
        ->with('location')
        ->get()
        ->groupBy('location_id');

        
        $locations = $business->businessLocationIDs();

        $unpaidBusinessLandLeaseDebts = LandLeaseDebt::whereIn('business_location_id', $locations)
        ->whereNotIn('status', [LeaseStatus::COMPLETE, LeaseStatus::LATE_PAYMENT, LeaseStatus::ON_TIME_PAYMENT, LeaseStatus::IN_ADVANCE_PAYMENT])
        ->get()
        ->groupBy('business_location_id');
        
        $paidBusinessLandLeaseDebts = LandLeaseDebt::whereIn('business_location_id', $locations)
        ->whereIn('status', [LeaseStatus::COMPLETE, LeaseStatus::LATE_PAYMENT, LeaseStatus::ON_TIME_PAYMENT, LeaseStatus::IN_ADVANCE_PAYMENT])
        ->get()
        ->groupBy('business_location_id');

        $unpaidBusinessInvestigationDebts = TaxAssessment::where('payment_status', '!=',ReturnStatus::COMPLETE)
            ->whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get()
            ->groupBy('business_location_id');

        $paidBusinessInvestigationDebts = TaxAssessment::where('payment_status', ReturnStatus::COMPLETE)
                ->whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                    $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                        $q->whereIn('business_location_id', $locations);
                    });
                })
                ->get()
                ->groupBy('business_location_id');
        
        $unpaidBusinessAuditDebts = TaxAssessment::where('payment_status', '!=',ReturnStatus::COMPLETE)
            ->whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                $query->whereHas('taxAuditLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get()
            ->groupBy('business_location_id');

        $paidBusinessAuditDebts = TaxAssessment::where('payment_status', ReturnStatus::COMPLETE)
                ->whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                    $query->whereHas('taxAuditLocations', function($q) use($locations) {
                        $q->whereIn('business_location_id', $locations);
                    });
                })
                ->get()
                ->groupBy('business_location_id');

        $unpaidBusinessVerificateionDebts = TaxAssessment::where('payment_status', '!=',ReturnStatus::COMPLETE)
            ->where('location_id', $locations)
            ->get()
            ->groupBy('location_id');

            // dd($unpaidBusinessVerificateionDebts);

        $paidBusinessVerificateionDebts = TaxAssessment::where('payment_status', ReturnStatus::COMPLETE)
            ->where('location_id', $locations)
            ->get()
            ->groupBy('location_id');

        return view('finance.taxpayer-ledger', compact(
            'business',
            'unpaidBusinessTaxReturnDebts',
            'paidBusinessTaxReturnDebts',
            'unpaidBusinessLandLeaseDebts',
            'paidBusinessLandLeaseDebts',
            'unpaidBusinessInvestigationDebts',
            'paidBusinessInvestigationDebts',
            'unpaidBusinessAuditDebts',
            'paidBusinessAuditDebts',
            'unpaidBusinessVerificateionDebts',
            'paidBusinessVerificateionDebts'
        ));
    }
}
