<?php

namespace App\Http\Livewire\Assesments;

use App\Models\BusinessTaxType;
use App\Models\PartialPayment;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PenaltyTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;
use App\Services\ZanMalipo\GepgResponse;

class TaxAssessmentPayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $assessment;

    public function mount($assessment)
    {
        $this->assessment = $assessment;
    }

    public function getGepgStatus($code)
    {
        $responseStatus = $this->getResponseCodeStatus($code);

        // Check if 'message' key exists using array_key_exists
        if (array_key_exists('message', $responseStatus)) {
            return $responseStatus['message'];
        } else {
            session()->flash('error', 'something went wrong, please contact your administrator');
            return back();
        }
    }


    public function refresh()
    {
        $this->assessment = get_class($this->assessment)::find($this->assessment->id);
        if (!$this->assessment) {
            session()->flash('error', 'Assessment not found.');
            return redirect()->back()->getTargetUrl();
        }
    }

    public function regenerate()
    {
        if (is_null($this->assessment->bill)) {
            $this->customAlert('error', 'Missing bill information. Please try again.');
            return back(); // Redirect back to the previous page
        }

        $response = $this->regenerateControlNo($this->assessment->bill);
        if ($response) {
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }


    public function generateBill() {
        try {
            if (get_class($this->assessment) === PartialPayment::class) {
                $partialPayment = $this->assessment;
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
                    $expire_date = Carbon::now()->addDays(30)->toDateTimeString(); // TODO: Recheck this date
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
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.assesments.assesment-payment');
    }
}
