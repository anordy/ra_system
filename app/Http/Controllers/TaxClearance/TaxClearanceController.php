<?php

namespace App\Http\Controllers\TaxClearance;

use App\Enum\Currencies;
use App\Enum\LeaseStatus;
use App\Enum\TaxClearanceStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TaxpayerLedger\TaxpayerLedgerController;
use App\Models\BusinessLocation;
use App\Models\Investigation\TaxInvestigation;
use App\Models\LandLeaseDebt;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Sequence;
use App\Models\SystemSetting;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxClearanceRequest;
use App\Models\Verification\TaxVerification;
use App\Traits\VerificationTrait;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;

class TaxClearanceController extends Controller
{
    use VerificationTrait;

    public function generateCertNo(){
//        fetch all approved certs
        $certs = TaxClearanceRequest::query()->select('id', 'certificate_number', 'status')->where('status', TaxClearanceStatus::APPROVED)->get();

        $year = date('Y');
        $sequence = '00001';
        DB::beginTransaction();
        try {
            foreach ($certs as $cert){
                $cert->certificate_number = $year.$sequence;
                $cert->save();

                $incrementedDigits = (int)$sequence + 1;
                $sequence = str_pad($incrementedDigits, strlen($sequence), '0', STR_PAD_LEFT);
            }

            $last_cert = Sequence::query()->where('name', Sequence::TAX_CLEARANCE)->first();
            if ($last_cert){
                if ($last_cert->update(['next_id' => $sequence, Sequence::TAX_CLEARANCE_YEAR => $year])){
                    DB::commit();
                    return 'success';
                }else{
                    DB::rollBack();
                    return 'Could not update sequence';
                }
            }else{
                DB::rollBack();
                return 'Sequence does not exist';
            }
        }catch (\Exception $ex){
            DB::rollBack();
            return $ex;
        }
    }

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

        $taxClearance = TaxClearanceRequest::where('id', $request_id)
            ->with('businessLocation')
            ->with('businessLocation.business')
            ->firstOrFail();


        $tax_return_debts = TaxReturn::where('location_id', $taxClearance->business_location_id)
            ->where('business_id', $taxClearance->business_id)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('financialMonth:id,name,due_date')
            ->get();

        // Verify Tax Returns/Debts
        foreach ($tax_return_debts as $return) {
            $this->verify($return);
        }

        $businessLocationId = $taxClearance->business_location_id;

        $tzsLedgers = TaxpayerLedgerController::getLedgerByCurrency(Currencies::TZS, $businessLocationId);
        $usdLedgers = TaxpayerLedgerController::getLedgerByCurrency(Currencies::USD, $businessLocationId);

        $ledgers = [
            'TZS' => TaxpayerLedgerController::joinLedgers($tzsLedgers['debitLedgers'], $tzsLedgers['creditLedgers']),
            'USD' => TaxpayerLedgerController::joinLedgers($usdLedgers['debitLedgers'], $usdLedgers['creditLedgers']),
        ];

        $tzsCreditSum = $tzsLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
        $tzsDebitSum = $tzsLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;
        $usdCreditSum = $usdLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
        $usdDebitSum = $usdLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;

        $summations = [
            'TZS' => ['credit' => $tzsCreditSum, 'debit' => $tzsDebitSum],
            'USD' => ['credit' => $usdCreditSum, 'debit' => $usdDebitSum],
        ];
        return view('tax-clearance.clearance-request', compact('taxClearance', 'summations', 'ledgers'));
    }

    public function approval($requestId)
    {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        $request_id = decrypt($requestId);
        
        $taxClearance = TaxClearanceRequest::where('id', $request_id)
            ->with('businessLocation')
            ->with('businessLocation.business')
            ->firstOrFail();


        $tax_return_debts = TaxReturn::where('location_id', $taxClearance->business_location_id)
            ->where('business_id', $taxClearance->business_id)
            ->where('payment_status', '!=', ReturnStatus::COMPLETE)
            ->with('financialMonth:id,name,due_date')
            ->get();

        // Verify Tax Returns/Debts
        foreach ($tax_return_debts as $return) {
            $this->verify($return);
        }

        $land_lease_debts = LandLeaseDebt::where('business_location_id', $taxClearance->business_location_id)
        ->whereNotIn('status', [LeaseStatus::PAID_PARTIALLY, LeaseStatus::COMPLETE, LeaseStatus::LATE_PAYMENT, LeaseStatus::ON_TIME_PAYMENT, LeaseStatus::IN_ADVANCE_PAYMENT])
        ->get();
        
        $locations = [$taxClearance->business_location_id];
        
        $investigationDebts = TaxAssessment::whereHasMorph('assessment', [TaxInvestigation::class], function($query) use($locations) {
                $query->whereHas('taxInvestigationLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();
        
        $auditDebts = TaxAssessment::whereHasMorph('assessment', [TaxAudit::class], function($query) use($locations) {
                $query->whereHas('taxAuditLocations', function($q) use($locations) {
                    $q->whereIn('business_location_id', $locations);
                });
            })
            ->get();

        $verificateionDebts = TaxAssessment::whereHasMorph('assessment', [TaxVerification::class])
                                ->where('location_id', $taxClearance->business_location_id)
                                ->get();
        
        return view('tax-clearance.approval', compact('tax_return_debts', 'taxClearance', 'land_lease_debts', 'investigationDebts', 'auditDebts', 'verificateionDebts'));
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
        $taxClearanceRequest = TaxClearanceRequest::findOrFail($taxClearanceRequestId);

        $location = $taxClearanceRequest->businessLocation;

        $url = env('TAXPAYER_URL') . route('qrcode-check.tax-clearance.certificate', ['clearanceId' =>  base64_encode(strval($clearanceId))], 0);
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(207)
            ->margin(0)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(36)
            ->logoResizeToWidth(36)
            ->labelText('')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());
        $dataUri = $result->getDataUri();

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        $pdf = PDF::loadView('tax-clearance.includes.certificate', compact('dataUri', 'location', 'taxClearanceRequest', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();
    }
}
