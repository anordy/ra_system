<?php

namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\Currencies;
use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Enum\PropertyOwnershipTypeStatus;
use App\Enum\SubVatConstant;
use App\Enum\TransactionType;
use App\Events\SendSms;
use App\Jobs\SendZanMalipoSMS;
use App\Models\Business;
use App\Models\BusinessTaxType;
use App\Models\BusinessType;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\Chartered\CharteredReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Taxpayer;
use App\Models\TaxRefund\TaxRefund;
use App\Models\TaxType;
use App\Models\TransactionFee;
use App\Models\Verification\TaxVerification;
use App\Models\ZmBill;
use App\Models\ZmBillChange;
use App\Services\Api\ZanMalipoInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


trait PaymentsTrait
{
    use ExchangeRateTrait, VerificationTrait, TaxpayerLedgerTrait;

    /**
     * @param ZmBill $bill
     * @return boolean
     */
    public function regenerateControlNo(ZmBill $bill): bool
    {
        $this->verify($bill);
        DB::beginTransaction();

        try {
            $billable = $bill->billable;

            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                if ($billable && isset($billable->status)) {
                    $billable->status = ReturnStatus::CN_GENERATED;
                }

                if ($billable && isset($billable->payment_status)) {
                    $billable->payment_status = ReturnStatus::CN_GENERATED;
                }

                $billable->save();

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";

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

    public function regeneratePublicServiceControlNo(ZmBill $bill): bool
    {
        $this->verify($bill);
        DB::beginTransaction();

        try {
            $billable = $bill->billable;
            if (config('app.env') != 'local') {
                $billable->payment_status = BillStatus::CN_GENERATING;
                $billable->save();
                (new ZanMalipoInternalService)->createBill($bill);
            } else {
                $billable->payment_status = BillStatus::CN_GENERATING;

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $billable->save();
                $bill->save();
            }
            DB::commit();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            $this->dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
            return true;
        } catch (\Exception$e) {
            DB::rollBack();
            Log::error($e->getMessage());
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

        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_name;
        $payer_email = $business->email;
        $payer_phone = $business->mobile;
        $description = "Return payment for {$payer_name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $tax_type->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $business->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $return->id;
        $billableType = get_class($return);

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
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";

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
            $bill->status = PaymentStatus::CANCELLED;
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
                'zm_bill_id' => $bill->id,
                'expire_date' => Carbon::parse($expireDate)->toDateTimeString(),
                'category' => 'update',
                'staff_id' => Auth::id(),
                'ack_date' => Carbon::now()->toDateTimeString(),
                'ack_status' => ZmResponse::SUCCESS,
            ]);
        }
    }

    public function landLeaseGenerateControlNo($leasePayment, $billItems)
    {
        if (isset($leasePayment->landLease->category) && $leasePayment->landLease->category === GeneralConstant::BUSINESS) {
            $payerId = $leasePayment->landLease->businessLocation->id;
            $payerName = $leasePayment->landLease->businessLocation->name;
            $payerMobile = $leasePayment->landLease->businessLocation->business->mobile;
            $payerEmail = $leasePayment->landLease->businessLocation->business->email;
            $payerType = Business::class;
        } else if (isset($leasePayment->landLease->category) && $leasePayment->landLease->category === GeneralConstant::SOLE_OWNER) {
            $payerId = $leasePayment->landLease->taxpayer->id;
            $payerName = $leasePayment->landLease->taxpayer->fullname;
            $payerMobile = $leasePayment->landLease->taxpayer->mobile;
            $payerEmail = $leasePayment->landLease->taxpayer->email;
            $payerType = Taxpayer::class;
        } else {
            $this->customAlert('warning', CustomMessage::error());
            return;
        }

        $tax_type = TaxType::where('code', TaxType::LAND_LEASE)->firstOrFail();
        $exchange_rate = $this->getExchangeRate('USD');

        $payer_type = $payerType;
        $payer_name = $payerName;
        $payer_email = $payerEmail;
        $payer_phone = $payerMobile;
        $location = ($leasePayment->landLease->region->name ?? '') . ', '. ($leasePayment->landLease->district->name ?? '') . ', ' . ($leasePayment->landLease->ward->name ?? '');
        $description = "Payment for Land Lease with DP number {$leasePayment->landLease->dp_number} at {$location}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = 'USD';
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $payerId;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $leasePayment->id;
        $billableType = get_class($leasePayment);

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
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }
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
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
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

        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_name;
        $payer_email = $business->email;
        $payer_phone = $business->mobile;
        $description = "{$debt->taxtype->name} Debt Payment for {$payer_name} {$debt->location->name} on {$debt->financialMonth->name} {$debt->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($debt->currency);
        $payer_id = $business->id;
        $expire_date = $debt->curr_payment_due_date;
        $billableId = $debt->id;
        $billableType = get_class($debt);

        $bill = ZmCore::createBill(
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
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();
        }
    }

    public function generateAssessmentDebtControlNo($debt)
    {
        $tax_type = TaxType::findOrFail($debt->tax_type_id);
        $taxTypes = TaxType::all();

        // If business tax type is of VAT take sub vat
        if ($tax_type->code == TaxType::VAT) {
            $businessTax = BusinessType::where('business_id', $debt->business_id)->where('tax_type_id', $debt->tax_type_id)->firstOrFail();
            $tax_type = SubVat::findOrFail($businessTax->sub_vat_id);
        }

        if ($debt->principal_amount > 0) {
            $billItems[] = [
                'billable_id' => $debt->id,
                'billable_type' => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount' => $debt->principal_amount,
                'currency' => $debt->currency,
                'gfs_code' => $tax_type->gfs_code,
                'tax_type_id' => $debt->tax_type_id,
            ];
        }

        if ($debt->penalty_amount > 0) {
            $billItems[] = [
                'billable_id' => $debt->id,
                'billable_type' => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount' => $debt->penalty_amount,
                'currency' => $debt->currency,
                'gfs_code' => $tax_type->gfs_code,
                'tax_type_id' => $taxTypes->where('code', TaxType::PENALTY)->firstOrFail()->id,
            ];
        }

        if ($debt->interest_amount > 0) {
            $billItems[] = [
                'billable_id' => $debt->id,
                'billable_type' => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount' => $debt->interest_amount,
                'currency' => $debt->currency,
                'gfs_code' => $tax_type->gfs_code,
                'tax_type_id' => $taxTypes->where('code', TaxType::INTEREST)->firstOrFail()->id,
            ];
        }

        $business = $debt->business;

        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_name;
        $payer_email = $business->email;
        $payer_phone = $business->mobile;
        $description = "{$debt->taxtype->name} Debt Payment for {$payer_name} {$debt->location->name}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($debt->currency);
        $payer_id = $business->id;
        $expire_date = $debt->curr_payment_due_date;
        $billableId = $debt->id;
        $billableType = get_class($debt);

        $bill = ZmCore::createBill(
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
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();
        }
    }

    public function generateWaivedAssessmentDisputeControlNo($assessment)
    {
        $tax_type = TaxType::findOrFail($assessment->tax_type_id);

        if ($assessment->outstanding_amount > 0) {
            $billItems[] = [
                'billable_id' => $assessment->id,
                'billable_type' => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount' => $assessment->outstanding_amount,
                'currency' => $assessment->currency,
                'gfs_code' => $tax_type->gfs_code,
                'tax_type_id' => $tax_type->id,
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
        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_name;
        $payer_email = $business->email;
        $payer_phone = $business->mobile;
        $description = "{$assessment->taxtype->name} dispute waiver for {$payer_name} in {$assessmentLocations}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $assessment->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id = Auth::id() != null ? Auth::id() : null;
        $exchange_rate = $this->getExchangeRate($assessment->currency);
        $payer_id = $business->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $assessment->id;
        $billableType = get_class($assessment);

        $bill = ZmCore::createBill(
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
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $assessment->payment_status = ReturnStatus::CN_GENERATED;

            $assessment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();
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
            (new ZanMalipoInternalService)->createBill($bill);
        }
    }

    public function generateReturnBillItems($tax_return)
    {
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
                        'billable_id' => $tax_return->id,
                        'billable_type' => get_class($tax_return),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $tax_return->principal,
                        'currency' => $tax_return->currency,
                        'gfs_code' => $taxType->gfs_code,
                        'tax_type_id' => $tax_return->tax_type_id,
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
            if ($tax_return->rdf_fee > 0) {
                $rdfTax = $taxTypes->where('code', TaxType::RDF)->firstOrFail();
                $billItems[] = [
                    'billable_id' => $tax_return->id,
                    'billable_type' => get_class($tax_return),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $tax_return->rdf_fee,
                    'currency' => $tax_return->currency,
                    'gfs_code' => $rdfTax->gfs_code,
                    'tax_type_id' => $rdfTax->id
                ];
            }
            if ($tax_return->road_license_fee > 0) {
                $rlfTax = $taxTypes->where('code', TaxType::ROAD_LICENSE_FEE)->firstOrFail();
                $billItems[] = [
                    'billable_id' => $tax_return->id,
                    'billable_type' => get_class($tax_return),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $tax_return->road_license_fee,
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

    public function generateReturnControlNumber($return)
    {
        if ($return->return_type === CharteredReturn::class) {
            $this->generateCharteredControlNumber($return);
            return;
        }

        $business = $return->business;
        $tax_type = BusinessTaxType::where('tax_type_id', $return->tax_type_id)->where('business_id', $return->business_id)->firstOrFail();
        $exchange_rate = $this->getExchangeRate($return->currency);

        // Generate return control no.
        $payer_type = get_class($business);
        $payer_name = $business->name ?? $business->taxpayer_name;
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
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";

                dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
            }
        }
    }

    public function generateAssessmentControlNumber($assessment)
    {
        $taxTypes = TaxType::all();

        $taxType = $assessment->taxtype;

        if (!$taxType->gfs_code) {
            $taxType = TaxType::where('code', TaxType::VERIFICATION)->first();
        }

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

            $bill = ZmCore::createBill(
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
                (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                $assessment->payment_status = ReturnStatus::CN_GENERATED;
                $assessment->save();

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();
                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }

    /**
     * @throws \Exception
     */
    public function generatePropertyTaxControlNumber($propertyPayment)
    {
        $taxType = TaxType::where('code', TaxType::PROPERTY_TAX)->firstOrFail();

        if (!$propertyPayment->ledger) {
            $this->recordLedger(
                TransactionType::DEBIT,
                get_class($propertyPayment),
                $propertyPayment->id,
                $propertyPayment->amount,
                $propertyPayment->interest,
                0,
                array_sum([$propertyPayment->amount, $propertyPayment->interest]),
                $taxType->id,
                Currencies::TZS,
                $propertyPayment->property->taxpayer_id
            );
        }

        $property = $propertyPayment->property;
        $exchange_rate = $this->getExchangeRate($propertyPayment->currency->iso);
        $payer_type = get_class($property->taxpayer);
        $payer_id = $property->taxpayer->id;

        if (!$payer_id) {
            Log::channel('property-tax')->warning("Couldn't create property payment bill for {$propertyPayment->id} as it miss payer's information");
            return;
        }

        if ($property->ownership->name === PropertyOwnershipTypeStatus::GOVERNMENT || $property->ownership->name === PropertyOwnershipTypeStatus::RELIGIOUS) {
            $payer_name = $property->institution_name;
        } else {
            if ($property->responsible) {
                $payer_name = $property->responsible->first_name . ' ' . $property->responsible->last_name;
                if (!trim($payer_name)) {
                    $payer_name = $property->taxpayer->fullname;
                }
            } else {
                $payer_name = $property->taxpayer->fullname;
            }
        }

        $payer_email = $property->taxpayer->email;
        $payer_phone = $property->taxpayer->mobile;
        $description = "Property Tax Payment for {$property->urn} - {$propertyPayment->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $propertyPayment->currency->iso;
        $createdby_type = Auth::user() ? get_class(Auth::user()) : 'job';
        $createdby_id = Auth::id() ?? 0;
        $expire_date = $propertyPayment->curr_payment_date;
        $billableId = $propertyPayment->id;
        $billableType = get_class($propertyPayment);
        $billItems = $this->generatePropertyTaxBillItems($propertyPayment, $taxType);

        if (!$billItems) {
            throw new \Exception('No bill items generated');
        }

        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $taxType->id,
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
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $propertyPayment->payment_status = BillStatus::CN_GENERATED;
            $propertyPayment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";

            if (env('APP_ENV') === 'production') {
                dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
            }
        }
    }

    public function generatePropertyTaxBillItems($propertyPayment, $taxType)
    {
        $property = $propertyPayment->property;
        $billItems[] = [
            'billable_id' => $propertyPayment->id,
            'billable_type' => get_class($property),
            'use_item_ref_on_pay' => 'N',
            'amount' => $propertyPayment->total_amount,
            'currency' => $propertyPayment->currency->iso,
            'gfs_code' => $taxType->gfs_code,
            'tax_type_id' => $taxType->id,
        ];

        return $billItems;
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function generateMvrControlNumber($mvr, $fee)
    {
        $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();

        if (!$mvr->ledger) {
            $this->recordDebitLedger($mvr, $fee->amount, $taxType->id);
        }

        $exchangeRate = 1;
        $bill = ZmCore::createBill(
            $mvr->id,
            get_class($mvr),
            $taxType->id,
            $mvr->taxpayer_id,
            Taxpayer::class,
            $mvr->taxpayer->fullname,
            $mvr->taxpayer->email,
            ZmCore::formatPhone($mvr->taxpayer->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "{$fee->name} for chassis number {$mvr->chassis->chassis_number}",
            ZmCore::PAYMENT_OPTION_EXACT,
            Currencies::TZS,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $mvr->id,
                    'billable_type' => get_class($mvr),
                    'tax_type_id' => $taxType->id,
                    'amount' => $fee->amount,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $fee->amount,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }


    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function generateMvrTransferOwnershipControlNumber($transfer, $fee)
    {
        $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();

        if (!$transfer->ledger) {
            $this->recordDebitLedger($transfer, $fee->amount, $taxType->id, $transfer->motor_vehicle->taxpayer_id);
        }

        $exchangeRate = 1;
        $bill = ZmCore::createBill(
            $transfer->id,
            get_class($transfer),
            $taxType->id,
            $transfer->agent_taxpayer_id,
            Taxpayer::class,
            $transfer->previous_owner->fullname,
            $transfer->previous_owner->email,
            ZmCore::formatPhone($transfer->previous_owner->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "{$fee->name} for chassis number {$transfer->motor_vehicle->chassis->chassis_number}",
            ZmCore::PAYMENT_OPTION_EXACT,
            Currencies::TZS,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $transfer->id,
                    'billable_type' => get_class($transfer),
                    'tax_type_id' => $taxType->id,
                    'amount' => $fee->amount,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $fee->amount,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function generateDLicenseControlNumber($license, $fee, $classFactor = 1)
    {
        $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();

        if (!$license->ledger) {
            $taxpayerId = $license->drivers_license_owner->taxpayer_id ?? $license->applicant->id;
            $this->recordDebitLedger($license, $fee->amount, $taxType->id, $taxpayerId);
        }

        $exchangeRate = 1;
        $bill = ZmCore::createBill(
            $license->id,
            get_class($license),
            $taxType->id,
            $license->driving_school_id,
            Taxpayer::class,
            $license->drivers_license_owner->fullname(),
            $license->drivers_license_owner->email,
            ZmCore::formatPhone($license->drivers_license_owner->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "{$fee->name} Driving License For {$license->drivers_license_owner->fullname()}",
            ZmCore::PAYMENT_OPTION_EXACT,
            Currencies::TZS,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $license->id,
                    'billable_type' => get_class($license),
                    'tax_type_id' => $taxType->id,
                    'amount' => $fee->amount * $classFactor,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $fee->amount * $classFactor,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function generateMvrDeregistrationControlNumber($mvr, $fee)
    {
        $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();

        if (!$mvr->ledger) {
            $this->recordDebitLedger($mvr, $fee->amount, $taxType->id);
        }

        $exchangeRate = 1;
        $bill = ZmCore::createBill(
            $mvr->id,
            get_class($mvr),
            $taxType->id,
            $mvr->taxpayer_id,
            Taxpayer::class,
            $mvr->taxpayer->fullname,
            $mvr->taxpayer->email,
            ZmCore::formatPhone($mvr->taxpayer->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "{$fee->name} for plate number {$mvr->registration->plate_number}",
            ZmCore::PAYMENT_OPTION_EXACT,
            Currencies::TZS,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $mvr->id,
                    'billable_type' => get_class($mvr),
                    'tax_type_id' => $taxType->id,
                    'amount' => $fee->amount,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $fee->amount,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }

    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public function generateMvrParticularChangeControlNumber($mvr, $fee)
    {
        $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
        $exchangeRate = 1;

        if (!$mvr->ledger) {
            $this->recordDebitLedger($mvr, $fee->amount, $taxType->id);
        }

        $bill = ZmCore::createBill(
            $mvr->id,
            get_class($mvr),
            $taxType->id,
            $mvr->taxpayer_id,
            Taxpayer::class,
            $mvr->taxpayer->fullname,
            $mvr->taxpayer->email,
            ZmCore::formatPhone($mvr->taxpayer->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "{$fee->name} for chassis number {$mvr->chassis->chassis_number}",
            ZmCore::PAYMENT_OPTION_EXACT,
            Currencies::TZS,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $mvr->id,
                    'billable_type' => get_class($mvr),
                    'tax_type_id' => $taxType->id,
                    'amount' => $fee->amount,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $fee->amount,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }

    public function generatePublicServiceControlNumber($psReturn) {
        $taxType = TaxType::select('id', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
        $exchangeRate = $this->getExchangeRate($psReturn->currency);
        $startDate = Carbon::create($psReturn->start_date)->format('d M Y H:i:s');
        $endDate = Carbon::create($psReturn->end_date)->format('d M Y H:i:s');

        if (!$psReturn->ledger) {
            $this->recordDebitLedger($psReturn, $psReturn->amount, $taxType->id);
        }

        $bill = ZmCore::createBill(
            $psReturn->id,
            get_class($psReturn),
            $taxType->id,
            $psReturn->taxpayer_id,
            Taxpayer::class,
            $psReturn->taxpayer->fullname,
            $psReturn->taxpayer->email,
            ZmCore::formatPhone($psReturn->taxpayer->mobile),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "Public Service Payment for {$psReturn->motor->mvr->plate_number} from {$startDate} to {$endDate}",
            ZmCore::PAYMENT_OPTION_EXACT,
            $psReturn->currency,
            $exchangeRate,
            $psReturn->taxpayer_id,
            Taxpayer::class,
            [
                [
                    'billable_id' => $psReturn->id,
                    'billable_type' => get_class($psReturn),
                    'tax_type_id' => $taxType->id,
                    'amount' => $psReturn->amount,
                    'currency' => $psReturn->currency,
                    'exchange_rate' => $exchangeRate,
                    'equivalent_amount' => $psReturn->amount * $exchangeRate,
                    'gfs_code' => $taxType->gfs_code
                ]
            ]
        );

        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        }else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();

            $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$bill->expireDate}.";
            dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
        }
    }

    /**
     * @throws \DOMException
     */
    public function generateTaxRefundControlNumber($taxRefund)
    {
        $taxType = TaxType::select('id')->where('code', TaxType::VAT)->firstOrFail();
        $subVat = SubVat::select('gfs_code')->where('code', SubVatConstant::IMPORTS)->firstOrFail();
        $exchangeRate = 1;
        $bill = ZmCore::createBill(
            $taxRefund->id,
            get_class($taxRefund),
            $taxType->id,
            $taxRefund->id,
            TaxRefund::class,
            $taxRefund->importer_name,
            null,
            ZmCore::formatPhone($taxRefund->phone_number),
            Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
            "Tax refund for {$taxRefund->importer_name}",
            ZmCore::PAYMENT_OPTION_EXACT,
            $taxRefund->currency,
            $exchangeRate,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $taxRefund->id,
                    'billable_type' => get_class($taxRefund),
                    'tax_type_id' => $taxType->id,
                    'amount' => $taxRefund->total_payable_amount,
                    'currency' => Currencies::TZS,
                    'exchange_rate' => 1,
                    'equivalent_amount' => $taxRefund->total_payable_amount,
                    'gfs_code' => $subVat->gfs_code
                ]
            ]
        );
        if (config('app.env') != 'local') {
            (new ZanMalipoInternalService)->createBill($bill);
        } else {
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->billable->payment_status = BillStatus::CN_GENERATED;
            $bill->billable->save();
            $bill->save();
            $this->flash('success', 'A control number for this verification has been generated successfully');
        }
    }


    public function generatePartialPaymentControlNo($partialPayment)
    {
        $assesment = $partialPayment->taxAssessment;
        $taxType = TaxType::findOrFail($assesment->tax_type_id, ['id', 'code', 'gfs_code']);

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

        $taxpayer = $assesment->business->taxpayer;
        $payer_type = get_class($assesment->business);
        $payer_name = $assesment->business->name;
        $payer_email = $taxpayer->email;
        $payer_phone = $taxpayer->mobile;
        $description = "Tax assesment payment for {$taxType->code}";
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
            $taxType->id,
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
            (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $partialPayment->payment_status = ReturnStatus::CN_GENERATED;
            $partialPayment->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status = 'pending';
            $zmBill->control_number = random_int(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateCharteredControlNumber($return)
    {
        $tax_type =  $return->tax_type_id;

        $exchange_rate = $this->getExchangeRate($return->currency);

        // Generate return control no.
        $payer_type = get_class($return->return);
        $payer_name = $return->return->company_name;
        $payer_email = null;
        $payer_phone = $return->return->mobile;


        $description = "Chartered Flight Return payment for {$payer_name}";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $return->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $return->return->id;
        $expire_date = $return->curr_payment_due_date;
        $billableId = $return->id;
        $billableType = get_class($return);

        $billItems = $this->generateReturnBillItems($return);

        if (count($billItems) > 0) {
            $bill = ZmCore::createBill(
                $billableId,
                $billableType,
                $tax_type,
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
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

                dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
            }
        }
    }

    public function generateLeasePartialPaymentControlNo($partialPayment)
    {
        $landLease = $partialPayment->landlease;

        $taxTypes = TaxType::select('id', 'code', 'gfs_code')->where('code', 'land-lease')->first();

        $billitems = [
            [
                'billable_id' => $partialPayment->id,
                'billable_type' => get_class($partialPayment),
                'use_item_ref_on_pay' => 'N',
                'amount' => roundOff($partialPayment->amount, $partialPayment->currency),
                'currency' => $partialPayment->currency,
                'gfs_code' => $taxTypes->gfs_code,
                'tax_type_id' => $taxTypes->id
            ],
        ];


        $taxpayer = $this->getTaxPayer($landLease)->first_name . ' ' . $this->getTaxPayer($landLease)->last_name;

        if ($landLease->category == 'business') {
            $payer_name = $landLease->businessLocation->business->name;
            $payer_type = get_class($landLease->businessLocation->business);
        } else {
            $payer_name = $taxpayer;
            $payer_type = get_class($this->getTaxPayer($landLease));
        }

        $payer_email = $this->getTaxPayer($landLease)->email;
        $payer_phone = $this->getTaxPayer($landLease)->mobile;
        $description = "Land Lease payment";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $partialPayment->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $exchange_rate = self::getExchangeRate($partialPayment->currency);
        $payer_id = $this->getTaxPayer($landLease)->id;
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
            (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $partialPayment->payment_status = ReturnStatus::CN_GENERATED;
            $partialPayment->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status = 'pending';
            $zmBill->control_number = random_int(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }
}
