<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\BusinessTaxType;
use App\Models\Investigation\TaxInvestigation;
use App\Models\PartialPayment;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TaxInvestigationAssessmentPaymentController extends Controller
{
    use PaymentsTrait, ExchangeRateTrait;
    public function index()
    {
        if (!Gate::allows('tax-investigation-assessment-view')) {
            abort(403);
        }
        return view('investigation.assessment-payments.index');
    }

    public function show($id)
    {
        if (!Gate::allows('tax-investigation-view')) {
            abort(403);
        }
        try {
            $partialPayment = PartialPayment::with('taxAssessment')->findOrFail(decrypt($id));
            $investigation = TaxInvestigation::findOrFail($partialPayment->taxAssessment->assessment_id);
            $taxAssessments = TaxAssessment::where('assessment_id', $investigation->id)
                ->where('assessment_type', get_class($investigation))->get();


            return view('investigation.assessment-payments.preview', compact('investigation', 'taxAssessments', 'partialPayment'));
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e,
            ]);

            return redirect()->back()->withError('Something went wrong Please contact your admin');
        }
    }

    public function approveReject(Request $request, $paymentId)
    {
        $request->validate([
            'comments' => 'required|string',
            'action' => 'required|in:approve,reject',
        ]);

        DB::beginTransaction();

        try {
            $partialPayment = PartialPayment::findOrFail(decrypt($paymentId));

            if ($request->action === 'approve') {
                // Perform approval logic
                $partialPayment->status = 'approved';
                $partialPayment->comments = $request->comments;
                $partialPayment->save();


                // Generate control number
                $controlNumber = $this->generateControlNumber($partialPayment);

                // Update partialPayment with control number
                $partialPayment->control_number = $controlNumber;
                $partialPayment->save();

                DB::commit();
                return redirect()->back()->with('success', 'Assessment approved and control number generated.');
            } else {
                // Perform rejection logic
                $partialPayment->status = 'rejected';
                $partialPayment->comments = $request->comments;
                $partialPayment->save();

                DB::commit();
                return redirect()->back()->with('success', 'Assessment Payments rejected.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e, [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Return an error response
            return redirect()->back()->withError('Something went wrong Please contact your admin');
        }
    }


    private function generateControlNumber($partialPayment)
    {
        $assesment = $partialPayment->taxAssessment;

        $taxType = TaxType::findOrFail($assesment->tax_type_id, ['id', 'code', 'gfs_code']);

        $taxTypes = TaxType::select('id', 'code', 'gfs_code')->where('code', 'investigation')->first();

        if ($taxType->code === TaxType::VAT) {
            $businessTaxType = BusinessTaxType::where('business_id', $assesment->business_id)
                ->where('tax_type_id', $taxType->id)->firstOrFail();
            $taxType = SubVat::findOrFail($businessTaxType->sub_vat_id, ['id', 'code', 'gfs_code']);
        } else if ($taxType->code === TaxType::AIRPORT_SERVICE_SAFETY_FEE) {
            $taxType = TaxType::where('code', TaxType::AIRPORT_SERVICE_CHARGE)->first();
        } else if ($taxType->code === TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE) {
            $taxType = TaxType::where('code', TaxType::SEAPORT_SERVICE_CHARGE)->first();
        }


        $billitems = [
            [
                'billable_id' => $partialPayment->id,
                'billable_type' => get_class($partialPayment),
                'use_item_ref_on_pay' => 'N',
                'amount' => roundOff($partialPayment->amount, $assesment->currency),
                'currency' => $assesment->currency,
                'gfs_code' => $taxType->gfs_code,
                'tax_type_id' => $taxType->id
            ],
        ];

        try {

            $taxpayer = $assesment->business->taxpayer;

            $payer_type = get_class($assesment->business);
            $payer_name = $assesment->business->name;
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "Tax Investigation assesment payment for {$taxType->code}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $assesment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = self::getExchangeRate($assesment->currency);
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addDays(30)->toDateTimeString();
            $billableId = $partialPayment->id;
            $billableType = get_class($partialPayment);

            DB::beginTransaction();

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $taxTypes->id,
                $payer_id,
                $payer_type,
                $payer_name,
                $payer_email,
                $payer_phone,
                $expire_date,
                $description,
                $payment_option,
                $currency,
                $exchange_rate,
                $createdby_id,
                $createdby_type,
                $billitems
            );
            DB::commit();

            if (config('app.env') != 'local') {
                $this->generateGeneralControlNumber($zmBill);
                $control_number = null;
            } else {
                // We are local
                $partialPayment->payment_status = ReturnStatus::CN_GENERATED;
                $partialPayment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->save();

                $control_number = $zmBill->control_number;
            }

            return $control_number;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
