<?php

namespace App\Http\Controllers\TaxClearance;

use App\Enum\Currencies;
use App\Enum\CustomMessage;
use App\Enum\TaxClearanceStatus;
use App\Http\Controllers\Controller;
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
use App\Models\TaxClearanceRequest;
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
use Illuminate\Support\Facades\Log;
use PDF;

class TaxClearanceController extends Controller
{
    use VerificationTrait;

    public function generateCertNo()
    {
        // fetch all approved certs
        $certs = TaxClearanceRequest::query()->select('id', 'certificate_number', 'status')->where('status', TaxClearanceStatus::APPROVED)->get();

        $year = date('Y');
        $sequence = '00001';
        DB::beginTransaction();
        try {
            foreach ($certs as $cert) {
                $cert->certificate_number = $year . $sequence;
                $cert->save();

                $incrementedDigits = (int)$sequence + 1;
                $sequence = str_pad($incrementedDigits, strlen($sequence), '0', STR_PAD_LEFT);
            }

            $last_cert = Sequence::query()->where('name', Sequence::TAX_CLEARANCE)->first();
            if ($last_cert) {
                if ($last_cert->update(['next_id' => $sequence])) {
                    DB::commit();
                    return 'success';
                } else {
                    DB::rollBack();
                    return 'Could not update sequence';
                }
            } else {
                DB::rollBack();
                return 'Sequence does not exist';
            }
        } catch (\Exception $ex) {
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

    public function viewRequest($requestId)
    {
        return $this->showView($requestId, 'tax-clearance.clearance-request');
    }

    public function approval($requestId)
    {
        return $this->showView($requestId, 'tax-clearance.approval');
    }

    private function showView($requestId, $viewName) {
        if (!Gate::allows('tax-clearance-view')) {
            abort(403);
        }

        try {

            $request_id = decrypt($requestId);

            $taxClearance = TaxClearanceRequest::query()
                ->select('id', 'business_id', 'business_location_id', 'reason', 'marking', 'approved_on', 'expire_on', 'status', 'deleted_at', 'created_at', 'updated_at', 'certificate_number')
                ->where('id', $request_id)
                ->with('businessLocation')
                ->with('businessLocation.business')
                ->firstOrFail();

            $tax_return_debts = TaxReturn::query()
                ->select('id', 'business_id', 'location_id', 'return_id', 'return_type', 'filed_by_id', 'filed_by_type', 'tax_type_id', 'financial_month_id', 'currency', 'principal', 'interest', 'penalty', 'infrastructure', 'airport_safety_fee', 'airport_service_charge', 'seaport_service_charge', 'seaport_transport_charge', 'infrastructure_znz_znz', 'infrastructure_znz_tz', 'rdf_fee', 'road_license_fee', 'withheld_tax', 'credit_brought_forward', 'total_amount', 'outstanding_amount', 'lumpsum_quarter', 'has_claim', 'is_nill', 'filing_method', 'return_status', 'payment_status', 'return_category', 'application_step', 'application_status', 'payment_method', 'paid_at', 'payment_due_date', 'filing_due_date', 'curr_payment_due_date', 'curr_filing_due_date', 'failed_verification', 'ci_payload', 'created_at', 'updated_at', 'sub_vat_id', 'vetting_status', 'marking', 'parent', 'deleted_at')
                ->where('location_id', $taxClearance->business_location_id)
                ->where('business_id', $taxClearance->business_id)
                ->where('payment_status', '!=', ReturnStatus::COMPLETE)
                ->with('financialMonth:id,name,due_date')
                ->get();

            // Verify Tax Returns/Debts
            foreach ($tax_return_debts as $return) {
                $this->verify($return);
            }

            $businessLocationId = $taxClearance->business_location_id;

            $tzsLedgers = (new \App\Http\Controllers\TaxpayerLedger\TaxpayerLedgerController)->getLedgerByCurrency(Currencies::TZS, $businessLocationId);
            $usdLedgers = (new \App\Http\Controllers\TaxpayerLedger\TaxpayerLedgerController)->getLedgerByCurrency(Currencies::USD, $businessLocationId);

            $ledgers = [
                'TZS' => (new \App\Http\Controllers\TaxpayerLedger\TaxpayerLedgerController)->joinLedgers($tzsLedgers['debitLedgers'], $tzsLedgers['creditLedgers']),
                'USD' => (new \App\Http\Controllers\TaxpayerLedger\TaxpayerLedgerController)->joinLedgers($usdLedgers['debitLedgers'], $usdLedgers['creditLedgers']),
            ];

            $tzsCreditSum = $tzsLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
            $tzsDebitSum = $tzsLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;
            $usdCreditSum = $usdLedgers['creditLedgers']->sum('total_credit_amount') ?? 0;
            $usdDebitSum = $usdLedgers['debitLedgers']->sum('total_debit_amount') ?? 0;

            $summations = [
                'TZS' => ['credit' => $tzsCreditSum, 'debit' => $tzsDebitSum],
                'USD' => ['credit' => $usdCreditSum, 'debit' => $usdDebitSum],
            ];

            return view($viewName, compact('taxClearance', 'summations', 'ledgers'));

        } catch (\Exception $exception) {
            Log::error('TAX-CLEARANCE-CONTROLLER-APPROVAL', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }


    public function certificate($clearanceId)
    {
        try {
            $taxClearanceRequestId = decrypt($clearanceId);
            $taxClearanceRequest = TaxClearanceRequest::findOrFail($taxClearanceRequestId, ['id', 'business_id', 'business_location_id', 'reason', 'marking', 'approved_on', 'expire_on', 'status', 'deleted_at', 'created_at', 'updated_at', 'certificate_number']);

            $location = $taxClearanceRequest->businessLocation;

            $url = config('modulesconfig.taxpayer_url') . route('qrcode-check.tax-clearance.certificate', ['clearanceId' => base64_encode($taxClearanceRequestId)], 0);
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

        } catch (\Exception $exception) {
            Log::error('TAX-CLEARANCE-CONTROLLER-CERTIFICATE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
