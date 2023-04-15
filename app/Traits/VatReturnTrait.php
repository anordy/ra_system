<?php

namespace App\Traits;


use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxCredit;
use App\Models\Claims\TaxCreditItem;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxType;
use Illuminate\Support\Facades\Log;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use function Livewire\str;

trait VatReturnTrait
{
    public $configs = [];

    public function claim($business_location_id)
    {
        $return = VatReturn::query()->selectRaw('payment_status, tax_credits.amount, payment_method, installments_count,
        tax_credits.id as credit_id, tax_claims.old_return_id, tax_claims.old_return_type, tax_claims.currency')
            ->leftJoin('tax_claims', 'tax_claims.old_return_id', '=', 'vat_returns.id')
            ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id')
            ->where('vat_returns.claim_status', '=', TaxClaimStatus::CLAIM)
            ->where('vat_returns.business_location_id', $business_location_id)
            ->where('tax_claims.status', 'approved')
            ->where('tax_credits.payment_status', '!=', 'paid')
            ->orderBy('tax_credits.id')->limit(1)
            ->first();
        return $return;
    }

    public function credit($business_location_id)
    {
        if (!empty($this->claim($business_location_id))) {
            $credit = TaxCredit::query()->findOrFail($this->claim($business_location_id)->credit_id);
            return $credit;
        }
    }

    public function totalPaid($business_location_id)
    {
        if (!empty($this->credit($business_location_id))) {
            $totalPaid = $this->credit($business_location_id)->items->sum('amount');
            return $totalPaid;
        }
    }

    public function creditBroughtForward($business_location_id)
    {
        if (!empty($this->claim($business_location_id))) {
            $remain = $this->claim($business_location_id)->amount - $this->totalPaid($business_location_id);
            if (is_numeric($this->claim($business_location_id)->amount) && $remain > 0) {
                if ($this->claim($business_location_id)->payment_method == 'full') {
                    return $this->claim($business_location_id)->amount;
                } else {
                    $credit = $this->claim($business_location_id)->amount / $this->claim($business_location_id)->installments_count;
                    return $credit;
                }
            } else {
                return 0;
            }
        }
        return 0;
    }

    public function savingClaimPayment($value, $business_location_id)
    {
        if ($this->claim($business_location_id)->amount == $value) {
            $items = new TaxCreditItem();
            $items->credit_id = $this->claim($business_location_id)->credit_id;
            $items->return_id = $this->claim($business_location_id)->old_return_id;
            $items->return_type = $this->claim($business_location_id)->old_return_type;
            $items->amount = $this->creditBroughtForward($business_location_id);
            $items->currency = $this->claim($business_location_id)->currency;
            $items->save();

            $this->updateCreditStatus($business_location_id);
        }
    }

    public function updateCreditStatus($business_location_id)
    {
        if ($this->claim($business_location_id)->payment_method == 'full') {
            $payment_status = ['payment_status' => 'paid'];
            $this->credit($business_location_id)->update($payment_status);
        } else {
            if ($this->claim($business_location_id)->amount == $this->totalPaid($business_location_id)) {
                $payment_status = ['payment_status' => 'paid'];
                $this->credit($business_location_id)->update($payment_status);
            } else {
                $payment_status = ['payment_status' => 'paid-partially'];
                $this->credit($business_location_id)->update($payment_status);
            }
        }
    }


}

