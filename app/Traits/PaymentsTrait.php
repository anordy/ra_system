<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\ZmBill;
use App\Events\SendSms;
use App\Models\TaxType;
use App\Enum\BillStatus;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Models\BusinessType;
use App\Models\ZmBillChange;
use App\Jobs\SendZanMalipoSMS;
use App\Models\TransactionFee;
use App\Models\BusinessTaxType;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Returns\Vat\SubVat;
use App\Services\ZanMalipo\ZmCore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\ZanMalipo\ZmResponse;
use App\Models\Returns\Port\PortReturn;
use App\Models\Investigation\TaxInvestigation;
use App\Services\Api\ZanMalipoInternalService;
use App\Models\Returns\Petroleum\PetroleumReturn;


trait PaymentsTrait
{
    use ExchangeRateTrait, VerificationTrait;

    /**
     * @param ZmBill $bill
     * @return boolean
     */
    public function regenerateControlNo(ZmBill $bill): bool
    {
        $this->verify($bill);
        
        DB::beginTransaction();

        try {
            $return = $bill->billable;

            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                $return->status = ReturnStatus::CN_GENERATED;
                $return->save();

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status       = 'pending';
                $bill->control_number   = rand(2000070001000, 2000070009999);
                $bill->save();

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s") ;
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

                event(new SendSms(SendZanMalipoSMS::SERVICE, null, [
                    'mobile_no' => ZmCore::formatPhone($bill->payer_phone_number),
                    'message' => $message
                ]));
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return false;
        }
    }

    /**
     * @param $return
     * @param $billItems
     * @return void
     * @throws \DOMException
     */
    public function generateControlNo($return, $billItems)
    {
        $business = $return->business;
        $tax_type = BusinessTaxType::where('tax_type_id', $return->tax_type_id)->where('business_id', $return->business_id)->firstOrFail();
        $exchange_rate = $this->getExchangeRate($tax_type->currency);

        $payer_type     = get_class($business);
        $payer_name     = $business->name ?? $business->taxpayer_name;
        $payer_email    = $business->email;
        $payer_phone    = $business->mobile;
        $description    = "Return payment for {$payer_name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency       = $tax_type->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id   = Auth::id();
        $payer_id       = $business->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $return->id;
        $billableType   = get_class($return);

        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $tax_type->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $return->status = ReturnStatus::CN_GENERATED;
            $return->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s") ;
            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));

            $this->flash('success', 'Your return was submitted, you will receive your payment information shortly - test');
        }
    }

    public function cancelBill(ZmBill $bill, $cancellationReason)
    {
        if (config('app.env') != 'local') {
            $cancelBill = (new ZanMalipoInternalService)->cancelBill($bill, $cancellationReason);

            return $cancelBill;
        } else {
            $bill->status              = PaymentStatus::CANCELLED;
            $bill->cancellation_reason = $cancellationReason ?? '';
            $bill->save();
        }
    }

    public function updateBill(ZmBill $bill, $expireDate)
    {
        if (!($expireDate instanceof Carbon)) {
            $expireDate = Carbon::make($expireDate);
        }
        if (config('app.env') != 'local') {
            $updateBill = (new ZanMalipoInternalService)->updateBill($bill, $expireDate->toDateTimeString());

            return $updateBill;
        } else {
            $bill->expire_date = $expireDate->toDateTimeString();
            $bill->save();

            $bill_change = ZmBillChange::create([
                'zm_bill_id'  => $bill->id,
                'expire_date' => Carbon::parse($expireDate)->toDateTimeString(),
                'category'    => 'update',
                'staff_id'    => Auth::id(),
                'ack_date'    => Carbon::now()->toDateTimeString(),
                'ack_status'  => ZmResponse::SUCCESS,
            ]);
        }
    }

    public function landLeaseGenerateControlNo($leasePayment, $billItems)
    {
        $taxpayer      = $leasePayment->taxpayer;
        $tax_type      = TaxType::where('code', TaxType::LAND_LEASE)->firstOrFail();
        $exchange_rate  = $this->getExchangeRate('USD');

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "Payment for Land Lease with DP number {$leasePayment->landLease->dp_number}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency       = 'USD';
        $createdby_type = get_class(Auth::user());
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $leasePayment->id;
        $billableType   = get_class($leasePayment);

        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $tax_type->id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $createBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $leasePayment->status = LeaseStatus::CN_GENERATED;
            $leasePayment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }
    }

    /**
     * @param $amount
     * @param $currency
     * @param $exchangeRate
     * @return float|int|void
     * @throws \Exception
     */
    public function getTransactionFee($amount, $currency, $exchangeRate = null)
    {
        if (!config('modulesconfig.charges_inclusive')){
            return 0;
        }
        
        if ($currency != 'TZS' && (!is_numeric($exchangeRate) || $exchangeRate <= 0)) {
            throw new \Exception('Please provide exchange rate for non TZS currency');
        }

        if ($currency != 'TZS') {
            $amount = $amount * $exchangeRate;
        }

        $transactionFee = TransactionFee::whereNull('maximum_amount')->select('minimum_amount', 'fee')->first();
        if($transactionFee == null){
            return 0;
        }
        $minFee = $transactionFee->minimum_amount;

        //if the amount exceed the maximum fee range we take the constant fee
        if ($minFee <= $amount) {
            $fee = $transactionFee->fee;
        } else {
            $fee = TransactionFee::where('minimum_amount', '<=', $amount)->where('maximum_amount', '>=', $amount)->pluck('fee')->firstOrFail();
            $fee = $fee * $amount;
        }
        
        if ($currency != 'TZS') {
            $fee = round($fee / $exchangeRate, 2);
        }

        return $fee;
    }

    /**
     * @param $bill
     * @param $billable
     * @return void
     * @throws \DOMException
     */
    public function sendBill($bill, $billable)
    {
        if (config('app.env') != 'local') {
            $response = ZmCore::sendBill($bill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
                $billable->status = BillStatus::CN_GENERATION_FAILED;
            }

            $billable->save();
        } else {
            // We are local
            $billable->status = BillStatus::CN_GENERATED;
            $billable->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly - test');
        }
    }

    public function generateDebtControlNo($debt)
    {
        $tax_type = TaxType::findOrFail($debt->tax_type_id);

        if ($tax_type->code == TaxType::VAT) {
            $tax_type = SubVat::findOrFail($debt->tax_type_id);
        }

        $billItems = $this->generateReturnBillItems($debt);

        $business = $debt->business;

        $payer_type     = get_class($business);
        $payer_name     = $business->name ?? $business->taxpayer_name;
        $payer_email    = $business->email;
        $payer_phone    = $business->mobile;
        $description    = "{$debt->taxtype->name} Debt Payment for {$payer_name} {$debt->location->name} on {$debt->financialMonth->name} {$debt->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency       = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($debt->currency);
        $payer_id = $business->id;
        $expire_date = $debt->curr_payment_due_date;
        $billableId = $debt->id;
        $billableType = get_class($debt);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $debt->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateAssessmentDebtControlNo($debt)
    {
        $tax_type = TaxType::findOrFail($debt->tax_type_id);
        $taxTypes = TaxType::all();

        // If business tax type is of VAT take sub vat
        if ($tax_type->code == TaxType::VAT) {
            $businessTax = BusinessType::where('business_id', $debt->business_id)->where('tax_type_id', $debt->tax_type_id)->firstOrFail();
            $tax_type = SubVat::findOrFail($businessTax);
        }

        if ($debt->principal_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->principal_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $debt->tax_type_id,
            ];
        }

        if ($debt->penalty_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->penalty_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::PENALTY)->firstOrFail()->id,
            ];
        }

        if ($debt->interest_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->interest_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::INTEREST)->firstOrFail()->id,
            ];
        }

        $business = $debt->business;

        $payer_type     = get_class($business);
        $payer_name     = $business->name ?? $business->taxpayer_id;
        $payer_email    = $business->email;
        $payer_phone    = $business->mobile;
        $description    = "{$debt->taxtype->name} Debt Payment for {$payer_name} {$debt->location->name}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency       = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($debt->currency);
        $payer_id = $business->id;
        $expire_date = $debt->curr_payment_due_date;
        $billableId = $debt->id;
        $billableType = get_class($debt);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $debt->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateWaivedAssessmentDisputeControlNo($assessment)
    {
        $tax_type = TaxType::findOrFail($assessment->tax_type_id);

        if ($assessment->principal_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->principal_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        if ($assessment->penalty_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->penalty_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        if ($assessment->interest_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->interest_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        $business = $assessment->business;

        if ($assessment->assessment_type == TaxAudit::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->taxAuditLocationNames() ?? 'Multiple business locations';
        } elseif ($assessment->assessment_type == TaxInvestigation::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->taxInvestigationLocationNames() ?? 'Multiple business locations';
        } elseif ($assessment->assessment_type == TaxVerification::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->location->name ?? 'Multiple business locations';
        } else {
            $assessmentLocations = 'Business location';
        }
        $payer_type     = get_class($business);
        $payer_name     = $business->name ?? $business->taxpayer_id;
        $payer_email    = $business->email;
        $payer_phone    = $business->mobile;
        $description    = "{$assessment->taxtype->name} dispute waiver for {$payer_name} in {$assessmentLocations}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency       = $assessment->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($assessment->currency);
        $payer_id = $business->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $assessment->id;
        $billableType = get_class($assessment);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $assessment->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $assessment->payment_status = ReturnStatus::CN_GENERATED;

            $assessment->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    /**
     * @param $return
     * @param $billItems
     * @return void
     * @throws \DOMException
     */
    public function generateGeneralControlNumber($bill)
    {
        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        }
    }

    public function generateReturnBillItems($tax_return) {
        $taxTypes = TaxType::all();
        if ($tax_return->return_type != PortReturn::class) {
            $taxType = TaxType::findOrFail($tax_return->tax_type_id);
        } else {
            if ($tax_return->airport_service_charge > 0 || $tax_return->airport_safety_fee > 0) {
                $taxType = TaxType::where('code', TaxType::AIRPORT_SERVICE_CHARGE)->firstOrFail();
            } else if ($tax_return->seaport_service_charge > 0 || $tax_return->seaport_transport_charge > 0) {
                $taxType = TaxType::where('code', TaxType::SEAPORT_SERVICE_CHARGE)->firstOrFail();
            } else {
                throw new \Exception('Invalid PORT return tax type');
            }
        }

        // If tax type is VAT use sub_vat tax type & gfs code
        if ($taxType->code == TaxType::VAT) {
            $taxType = SubVat::findOrFail($tax_return->sub_vat_id);
        }

        $billItems = [];

        /**
         * Port return principal is handled separately
         */
        if ($tax_return->return_type != PortReturn::class) {
            // Principal is the main tax type name
            if (!$tax_return->has_claim) {
                if ($tax_return->principal > 0) {
                    $billItems[] = [
                            'billable_id'         => $tax_return->id,
                            'billable_type'       => get_class($tax_return),
                            'use_item_ref_on_pay' => 'N',
                            'amount'              => $tax_return->principal,
                            'currency'            => $tax_return->currency,
                            'gfs_code'            => $taxType->gfs_code,
                            'tax_type_id'         => $tax_return->tax_type_id,
                    ];
                }
            } 
        }

        if ($tax_return->penalty > 0) {
            $billItems[] = [
                'billable_id' => $tax_return->id,
                'billable_type' => get_class($tax_return),
                'use_item_ref_on_pay' => 'N',
                'amount' => $tax_return->penalty,
                'currency' => $tax_return->currency,
                'gfs_code' => $taxType->gfs_code,
                'tax_type_id' => $taxTypes->where('code', TaxType::PENALTY)->firstOrFail()->id
            ];
        }
        if ($tax_return->interest > 0) {
            $billItems[] = [
                'billable_id' => $tax_return->id,
                'billable_type' => get_class($tax_return),
                'use_item_ref_on_pay' => 'N',
                'amount' => $tax_return->interest,
                'currency' => $tax_return->currency,
                'gfs_code' => $taxType->gfs_code,
                'tax_type_id' => $taxTypes->where('code', TaxType::INTEREST)->firstOrFail()->id
            ];
        }
        if ($tax_return->infrastructure > 0) {
            $infraTax = $taxTypes->where('code', TaxType::INFRASTRUCTURE)->firstOrFail();
            $billItems[] = [
                'billable_id' => $tax_return->id,
                'billable_type' => get_class($tax_return),
                'use_item_ref_on_pay' => 'N',
                'amount' => $tax_return->infrastructure,
                'currency' => $tax_return->currency,
                'gfs_code' => $infraTax->gfs_code,
                'tax_type_id' => $infraTax->id
            ];
        }

        if ($tax_return->return_type == PetroleumReturn::class) {
                if ($tax_return->rdf_tax > 0) {
                    $rdfTax = $taxTypes->where('code', TaxType::RDF)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->rdf_tax,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $rdfTax->gfs_code,
                        'tax_type_id' => $rdfTax->id
                    ];
                }
                if ($tax_return->road_lincence_fee > 0) {
                    $rlfTax = $taxTypes->where('code', TaxType::ROAD_LICENSE_FEE)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->road_lincence_fee,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $rlfTax->gfs_code,
                        'tax_type_id' => $rlfTax->id
                    ];
                }
        } elseif ($tax_return->return_type == PortReturn::class) {
                if ($tax_return->airport_service_charge > 0) {
                    $airportServiceChargeTax = $taxTypes->where('code', TaxType::AIRPORT_SERVICE_CHARGE)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->airport_service_charge,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $airportServiceChargeTax->gfs_code,
                        'tax_type_id' => $airportServiceChargeTax->id
                    ];
                }

                if ($tax_return->airport_safety_fee > 0) {
                    $airportSafetyFeeTax = $taxTypes->where('code', TaxType::AIRPORT_SAFETY_FEE)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->airport_safety_fee,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $airportSafetyFeeTax->gfs_code,
                        'tax_type_id' => $airportSafetyFeeTax->id
                    ];
                }

                if ($tax_return->seaport_service_charge > 0) {
                    $seaportServiceChargeTax = $taxTypes->where('code', TaxType::SEAPORT_SERVICE_CHARGE)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->seaport_service_charge,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $seaportServiceChargeTax->gfs_code,
                        'tax_type_id' => $seaportServiceChargeTax->id
                    ];
                }

                if ($tax_return->seaport_transport_charge > 0) {
                    $seaportTransportChargeTax = $taxTypes->where('code', TaxType::SEAPORT_TRANSPORT_CHARGE)->firstOrFail();
                    $billItems[] = [
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->seaport_transport_charge,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $seaportTransportChargeTax->gfs_code,
                        'tax_type_id' => $seaportTransportChargeTax->id
                    ];
                }

        }

        return $billItems;
    }

    public function generateReturnControlNumber($return) {
        $business = $return->business;
        $tax_type = BusinessTaxType::where('tax_type_id', $return->tax_type_id)->where('business_id', $return->business_id)->firstOrFail();
        $exchange_rate = $this->getExchangeRate($return->currency);

        // Generate return control no.
        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_id;
        $payer_email = $business->email;
        $payer_phone = $business->mobile;
        if ($return->table == 'lump_sum_returns') {
            $description = "Lump Sum Payments for {$payer_name}  {$this->fillingMonth['name']} ";
        } else {
            $description = "Return payment for {$payer_name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
        }
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $return->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $business->id;
        $expire_date = $return->curr_payment_due_date;
        $billableId = $return->id;
        $billableType = get_class($return);

        $billItems = $this->generateReturnBillItems($return);

        if (count($billItems) > 0) {
            $bill = ZmCore::createBill(
                $billableId,
                $billableType,
                $tax_type->tax_type_id,
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
                $billItems
            );
    
            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                $return->payment_status = ReturnStatus::CN_GENERATED;
                $return->return->status = ReturnStatus::CN_GENERATED;
                $return->return->save();
                $return->save();
    
                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = rand(2000070001000, 2000070009999);
                $bill->save();
    
                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s") ;
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";
    
                dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
            }
        }


    }

    public function generateAssessmentControlNumber($assessment)
    {
        $taxTypes = TaxType::all();
        $taxType = $assessment->taxtype;

        DB::beginTransaction();

        try {
            $billitems = [];

            if ($assessment->principal_amount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->principal_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $taxType->id
                ];
            }

            if ($assessment->interest_amount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->interest_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $taxTypes->where('code', 'interest')->firstOrFail()->id
                ];
            }

            if ($assessment->penalty_amount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->penalty_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $taxTypes->where('code', 'penalty')->firstOrFail()->id
                ];
            }

            $business = $assessment->business;

            $payer_type = get_class($business);
            $payer_name = $business->name ?? $business->taxpayer_name;
            $payer_email = $business->email;
            $payer_phone = $business->mobile;
            $description = "{$taxType->name} Verification Assessment for {$payer_name}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $assessment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = $this->getExchangeRate($assessment->currency);
            $payer_id = $business->id;
            $expire_date = Carbon::now()->addDays(30)->endOfDay();
            $billableId = $assessment->id;
            $billableType = get_class($assessment);
            $taxType = $taxType->id;

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $taxType,
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
            } else {
                // We are local
                $assessment->payment_status = ReturnStatus::CN_GENERATED;
                $assessment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();
                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }
}
