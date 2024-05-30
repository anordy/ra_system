<?php

namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Enum\SubVatConstant;
use App\Events\SendSms;
use App\Jobs\SendZanMalipoSMS;
use App\Models\Currency;
use App\Models\Investigation\TaxInvestigation;
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
use function Symfony\Component\String\b;

trait PaymentsTrait
{
    use ExchangeRateTrait, VerificationTrait;

    /**
     * @param ZmBill $bill
     * @return boolean
     */
    public function regenerateControlNo(ZmBill $bill): bool
    {
        try {
            $this->verify($bill);

            DB::beginTransaction();

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

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = BillStatus::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);

                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
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
            Log::error('TRAITS-PAYMENTS-TRAIT-REGENERATE-CONTROLNO', [$e]);
            return false;
        }
    }

    /**
     * @param $return
     * @param $billItems
     * @return void
     * @throws \DOMException|\Exception
     */
    public function generateControlNo($return, $billItems)
    {
        try {
            $business = $return->business;
            $exchange_rate = $this->getExchangeRate($return->currency);
            $payer_type = get_class($business);
            $payer_name = $business->name ?? $business->taxpayer_name;
            $payer_email = $business->email;
            $payer_phone = $business->mobile;
            $description = "Return payment for {$payer_name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $return->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $payer_id = $business->id;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billableId = $return->id;
            $billableType = get_class($return);

            $bill = ZmCore::createBill(
                $billableId,
                $billableType,
                $return->tax_type_id,
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

                if (!$return->save()) throw new \Exception('Failed to Save Bill');

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = BillStatus::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);

                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

                dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));

                $this->flash('success', 'Your return was submitted, you will receive your payment information shortly - test');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-CONTROLNO', [$e]);
            throw $e;
        }
    }

    public function cancelBill(ZmBill $bill, $cancellationReason)
    {
        try {
            if (config('app.env') != 'local') {
                return (new ZanMalipoInternalService)->cancelBill($bill, $cancellationReason);
            } else {
                $bill->status = PaymentStatus::CANCELLED;
                $bill->cancellation_reason = $cancellationReason ?? '';
                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

            }
        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-CANCEL-BILL', [$e]);
            throw $e;
        }
    }

    public function updateBill(ZmBill $bill, $expireDate)
    {
        try {
            if (!($expireDate instanceof Carbon)) {
                $expireDate = Carbon::make($expireDate);
            }
            if (config('app.env') != 'local') {
                return (new ZanMalipoInternalService)->updateBill($bill, $expireDate->toDateTimeString());
            } else {
                $bill->expire_date = $expireDate->toDateTimeString();

                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                ZmBillChange::create([
                    'zm_bill_id' => $bill->id,
                    'expire_date' => Carbon::parse($expireDate)->toDateTimeString(),
                    'category' => 'update',
                    'staff_id' => Auth::id(),
                    'ack_date' => Carbon::now()->toDateTimeString(),
                    'ack_status' => ZmResponse::SUCCESS,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-UPDATE-BILL', [$e]);
            throw $e;
        }
    }

    public function landLeaseGenerateControlNo($leasePayment, $billItems)
    {
        try {
            $taxpayer = $leasePayment->taxpayer;
            $tax_type = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::LAND_LEASE)->firstOrFail();
            $exchange_rate = $this->getExchangeRate('USD');

            $payer_type = get_class($taxpayer);
            $payer_name = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "Payment for Land Lease with DP number {$leasePayment->landLease->dp_number}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = 'USD';
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $payer_id = $taxpayer->id;
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
                if (!$leasePayment->save()) throw new \Exception('Failed to Save Lease Payment');

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = BillStatus::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);
                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-LANDLEASE-GENERATE-CONTROLNO', [$e]);
            throw $e;
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
        try {
            if (!config('modulesconfig.charges_inclusive')) {
                return 0;
            }

            if ($currency != Currency::TZS && (!is_numeric($exchangeRate) || $exchangeRate <= 0)) {
                throw new \Exception('Please provide exchange rate for non TZS currency');
            }

            if ($currency != Currency::TZS) {
                $amount = $amount * $exchangeRate;
            }

            $transactionFee = TransactionFee::whereNull('maximum_amount')->select('minimum_amount', 'fee')->first();
            if ($transactionFee == null) {
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

            if ($currency != Currency::TZS) {
                $fee = round($fee / $exchangeRate, 2);
            }

            return $fee;

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GET-TRANSACTION-FEE', [$e]);
            throw $e;
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
        try {
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($bill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                    $billable->status = BillStatus::CN_GENERATION_FAILED;
                }

                if (!$billable->save()) throw new \Exception('Failed to Save Billable');

            } else {
                // We are local
                $billable->status = BillStatus::CN_GENERATED;
                if (!$billable->save()) throw new \Exception('Failed to Save Billable');


                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = BillStatus::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);
                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                session()->flash('success', 'Your request was submitted, you will receive your payment information shortly - test');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-SEND-BILL', [$e]);
            throw $e;
        }
    }

    public function generateDebtControlNo($debt)
    {
        try {
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
                if (!$debt->save()) throw new \Exception('Failed to Save Bill');


                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');

            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-DEBT-CONTROLNO', [$e]);
            throw $e;
        }
    }

    public function generateAssessmentDebtControlNo($debt)
    {
        try {
            $tax_type = TaxType::findOrFail($debt->tax_type_id, ['id', 'name', 'code', 'gfs_code']);
            $taxTypes = TaxType::select('id', 'name', 'code', 'gfs_code')->get();

            // If business tax type is of VAT take sub vat
            if ($tax_type->code == TaxType::VAT) {
                $tax_type = SubVat::findOrFail($debt->sub_vat_id, ['id', 'name', 'code', 'gfs_code']);
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
                if (!$debt->save()) throw new \Exception('Failed to Save Debt');

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');

            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-ASSESSMENT-DEBT-CONTROLNO', [$e]);
            throw $e;
        }
    }

    public function generateWaivedAssessmentDisputeControlNo($assessment)
    {
        try {
            $tax_type = TaxType::findOrFail($assessment->tax_type_id, ['id', 'name', 'code', 'gfs_code']);

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
                if (!$assessment->save()) throw new \Exception('Failed to Save Assessment');

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');

            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-WAIVED-ASSESSMENT-DISPUTE-CONTROLNO', [$e]);
            throw $e;
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
        try {
            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            }
        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-GENERAL-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generateReturnBillItems($tax_return)
    {
        try {
            $taxTypes = TaxType::select('id', 'name', 'code', 'gfs_code')->get();
            if ($tax_return->return_type != PortReturn::class) {
                $taxType = TaxType::findOrFail($tax_return->tax_type_id, ['id', 'name', 'code', 'gfs_code']);
            } else {
                if ($tax_return->airport_service_charge > 0 || $tax_return->airport_safety_fee > 0) {
                    $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::AIRPORT_SERVICE_CHARGE)->firstOrFail();
                } else if ($tax_return->seaport_service_charge > 0 || $tax_return->seaport_transport_charge > 0) {
                    $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::SEAPORT_SERVICE_CHARGE)->firstOrFail();
                } else {
                    throw new \Exception('Invalid PORT return tax type');
                }
            }

            // If tax type is VAT use sub_vat tax type & gfs code
            if ($taxType->code == TaxType::VAT) {
                $taxType = SubVat::findOrFail($tax_return->sub_vat_id, ['id', 'name', 'code', 'gfs_code']);
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

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-RETURN-BILL-ITEMS', [$e]);
            throw $e;
        }
    }

    public function generateReturnControlNumber($return)
    {
        try {
            $business = $return->business;
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
                    $return->tax_type_id,
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
                    if (!$return->return->save()) throw new \Exception('Failed to Save Child return');
                    if (!$return->save()) throw new \Exception('Failed to Save Return');

                    // Simulate successful control no generation
                    $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $bill->zan_status = BillStatus::PENDING;
                    $bill->control_number = random_int(2000070001000, 2000070009999);
                    if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                    $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                    $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

                    dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
                }
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-RETURN-CONTROL-NUMBER', [$e]);
            throw $e;
        }

    }

    public function generateAssessmentControlNumber($assessment)
    {
        try {
            $taxTypes = TaxType::select('id', 'name', 'code', 'gfs_code')->get();
            $taxType = $assessment->taxtype;

            if (!$taxType->gfs_code) {
                throw new \Exception('Invalid or Missing gfs code');
            }

            DB::beginTransaction();

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
                if (!$assessment->save()) throw new \Exception('Failed to Save Assessment');

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');

                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-ASSESSMENT-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generatePropertyTaxControlNumber($propertyPayment)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PROPERTY_TAX)->firstOrFail();

            $property = $propertyPayment->property;

            $exchange_rate = $this->getExchangeRate($propertyPayment->currency->iso);

            $payer_type = get_class($property->taxpayer);
            $payer_id = $property->taxpayer->id;
            $payer_name = $property->responsible->first_name . ' ' . $property->responsible->last_name;
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
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                $propertyPayment->payment_status = BillStatus::CN_GENERATED;
                $propertyPayment->save();
                if (!$propertyPayment->save()) throw new \Exception('Failed to Property Payment');

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = BillStatus::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);
                if (!$bill->save()) throw new \Exception('Failed to Save Bill');

                $expireDate = Carbon::parse($bill->expire_date)->format("d M Y H:i:s");
                $message = "Your control number for ZRA is {$bill->control_number} for {$bill->description}. Please pay {$bill->currency} {$bill->amount} before {$expireDate}.";

                if (env('APP_ENV') === 'production') {
                    dispatch(new SendZanMalipoSMS(ZmCore::formatPhone($bill->payer_phone_number), $message));
                }

            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-PROPERTY-TAX-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generatePropertyTaxBillItems($propertyPayment, $taxType)
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-PROPERTY-TAX-BILL-ITEMS', [$e]);
            throw $e;
        }
    }

    public function generateMvrControlNumber($mvr, $fee)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            $exchangeRate = $this->getExchangeRate(Currency::TZS);

            $zmBill = ZmCore::createBill(
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
                Currency::TZS,
                $exchangeRate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $mvr->id,
                        'billable_type' => get_class($mvr),
                        'tax_type_id' => $taxType->id,
                        'amount' => $fee->amount,
                        'currency' => Currency::TZS,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $fee->amount,
                        'gfs_code' => $taxType->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-MVR-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }


    public function generateMvrTransferOwnershipControlNumber($transfer, $fee)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            $exchangeRate = $this->getExchangeRate(Currency::TZS);

            $zmBill = ZmCore::createBill(
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
                Currency::TZS,
                $exchangeRate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $transfer->id,
                        'billable_type' => get_class($transfer),
                        'tax_type_id' => $taxType->id,
                        'amount' => $fee->amount,
                        'currency' => Currency::TZS,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $fee->amount,
                        'gfs_code' => $taxType->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-MVR-TRANSFER-OWNERSHIP-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generateMvrStatusChangeConntrolNumber($mvr, $fee)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            $exchangeRate = $this->getExchangeRate(Currency::TZS);

            $zmBill = ZmCore::createBill(
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
                'TZS',
                $exchangeRate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $mvr->id,
                        'billable_type' => get_class($mvr),
                        'tax_type_id' => $taxType->id,
                        'amount' => $fee->amount,
                        'currency' => Currency::TZS,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $fee->amount,
                        'gfs_code' => $taxType->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-MVR-STATUS-CHANGE-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generateDLicenseControlNumber($license, $fee)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            $exchangeRate = $this->getExchangeRate(Currency::TZS);

            $zmBill = ZmCore::createBill(
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
                Currency::TZS,
                $exchangeRate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $license->id,
                        'billable_type' => get_class($license),
                        'tax_type_id' => $taxType->id,
                        'amount' => $fee->amount,
                        'currency' => Currency::TZS,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $fee->amount,
                        'gfs_code' => $taxType->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-DL-LICENSE-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generateMvrDeregistrationControlNumber($mvr, $fee)
    {
        try {
            $taxType = TaxType::select('id', 'name', 'code', 'gfs_code')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            $exchangeRate = $this->getExchangeRate(Currency::TZS);

            $zmBill = ZmCore::createBill(
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
                Currency::TZS,
                $exchangeRate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $mvr->id,
                        'billable_type' => get_class($mvr),
                        'tax_type_id' => $taxType->id,
                        'amount' => $fee->amount,
                        'currency' => Currency::TZS,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $fee->amount,
                        'gfs_code' => $taxType->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');
                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-MVR-DEREGISTRATION-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }

    public function generateTaxRefundControlNumber($taxRefund)
    {
        try {
            $taxType = TaxType::select('id')->where('code', TaxType::VAT)->firstOrFail();
            $subVat = SubVat::select('gfs_code')->where('code', SubVatConstant::IMPORTS)->firstOrFail();
            $exchangeRate = $this->getExchangeRate($taxRefund->currency);

            $zmBill = ZmCore::createBill(
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
                        'currency' => $taxRefund->currency,
                        'exchange_rate' => $exchangeRate,
                        'equivalent_amount' => $taxRefund->total_payable_amount,
                        'gfs_code' => $subVat->gfs_code
                    ]
                ]
            );
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = BillStatus::PENDING;
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->billable->payment_status = BillStatus::CN_GENERATED;
                if (!$zmBill->billable->save()) throw new \Exception('Failed to Save Billable');
                if (!$zmBill->save()) throw new \Exception('Failed to Save Bill');

                $this->flash('success', 'A control number for this verification has been generated successfully');
            }

        } catch (\Exception $e) {
            Log::error('TRAITS-PAYMENTS-TRAIT-GENERATE-TAX-REFUND-CONTROL-NUMBER', [$e]);
            throw $e;
        }
    }


}