<?php

namespace App\Http\Controllers\TaxClearance;

use App\Enum\LeaseStatus;
use PDF;
use Carbon\Carbon;
use App\Models\Debts\Debt;
use App\Enum\ReturnCategory;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxClearanceRequest;
use App\Http\Controllers\Controller;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Gate;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Investigation\TaxInvestigationLocation;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;

class TaxClearanceController extends Controller
{
    public function index()
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        return view('tax-clearance.index');
    }
    public function requestList()
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        return view('tax-clearance.requests');
    }

    public function viewRequest($requestId)
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        $request_id = decrypt($requestId);

        $taxClearence = TaxClearanceRequest::where('id', $request_id)
            ->with('businessLocation')
            ->with('businessLocation.business')
            ->first();


        $tax_return_debts = TaxReturn::where('location_id', $taxClearence->business_location_id)
            ->where('business_id', $taxClearence->business_id)
            ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('installment')
            ->get();

        $land_lease_debts = LandLeaseDebt::where('business_location_id', $taxClearence->business_location_id)
        ->where('status', LeaseStatus::PENDING)
        ->get();

        $locations = [$taxClearence->business_location_id];

        $investigationDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();
        
        $auditDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                $query->whereHas('taxAuditLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();

        $verificateionDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
                                ->where('location_id', $taxClearence->business_location_id)
                                ->get();

        return view('tax-clearance.clearance-request', compact('tax_return_debts', 'taxClearence', 'land_lease_debts', 'investigationDebts', 'auditDebts', 'verificateionDebts'));
    }

    public function approval($requestId)
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        $request_id = decrypt($requestId);

        $taxClearence = TaxClearanceRequest::where('id', $request_id)
            ->with('businessLocation')
            ->with('businessLocation.business')
            ->first();


        $tax_return_debts = TaxReturn::where('location_id', $taxClearence->business_location_id)
            ->where('business_id', $taxClearence->business_id)
            ->whereIn('return_category', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('installment')
            ->get();

        $land_lease_debts = LandLeaseDebt::where('business_location_id', $taxClearence->business_location_id)
        ->where('status', LeaseStatus::PENDING)
        ->get();

        
        $locations = [$taxClearence->business_location_id];

        $investigationDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();
        
        $auditDebts = TaxAssessment::whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
            ->whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                $query->whereHas('taxAuditLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();

        $verificateionDebts = TaxAssessment::whereHasMorph('assessment', [TaxVerification::class])->whereIn('assessment_step', [ReturnCategory::DEBT, ReturnCategory::OVERDUE])
                                ->where('location_id', $taxClearence->business_location_id)
                                ->get();

        return view('tax-clearance.approval', compact('tax_return_debts', 'taxClearence', 'land_lease_debts', 'investigationDebts', 'auditDebts', 'verificateionDebts'));
    }

    public function generateReturnsDebts($business_location_id)
    {
        $now = Carbon::now();

        $returnModels = [
            StampDutyReturn::class,
            MnoReturn::class,
            VatReturn::class,
            MmTransferReturn::class,
            HotelReturn::class,
            PetroleumReturn::class,
            // PortReturn::class,
            EmTransactionReturn::class,
            BfoReturn::class,
            LumpSumReturn::class,
        ];

        $return_debts = [];

        foreach ($returnModels as $model) {
            if ($model == PortReturn::class) {
                $fields = 'total_amount_due_with_penalties_tzs, total_amount_due_with_penalties_usd, total_vat_payable_tzs, total_vat_payable_usd, interest_usd, interest_tzs, penalty_usd, penalty_tzs';
            } elseif ($model == MmTransferReturn::class || $model == EmTransactionReturn::class) {
                $fields = 'total_amount_due_with_penalties, total_amount_due';
            } else {
                $fields = 'total_amount_due_with_penalties, total_amount_due, interest, penalty';
            }

            $table_name = $model::query()->getQuery()->from;

            $returns = $model
                ::selectRaw(
                    '
                ' .
                        $table_name .
                        '.id,
                business_id,
                business_location_id,
                tax_type_id,
                currency,
                ' .
                        $fields .
                        ',
                financial_months.name
            ',
                )
                ->leftJoin('financial_months', 'financial_months.id', '' . $table_name . '.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('business_location_id', $business_location_id)
                ->where('' . $table_name . '.status', '!=', ReturnStatus::COMPLETE)
                ->where('financial_months.due_date', '<', $now)
                ->get();

            foreach ($returns as $return) {
                $return_debts[] = $return;
            }
        }

        return $return_debts;
    }

    public function certificate($clearanceId)
    {
        $taxClearanceRequestId = decrypt($clearanceId);
        $taxClearanceRequest = TaxClearanceRequest::find($taxClearanceRequestId);

        $location = $taxClearanceRequest->businessLocation;

        $pdf = PDF::loadView('tax-clearance.includes.certificate', compact('location', 'taxClearanceRequest'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }
}
