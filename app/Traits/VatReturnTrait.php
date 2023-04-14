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

    public function claim()
    {
        $return = VatReturn::query()->selectRaw('payment_status, tax_credits.amount, payment_method, installments_count,
        tax_credits.id as credit_id, tax_claims.old_return_id, tax_claims.old_return_type, tax_claims.currency')
            ->leftJoin('tax_claims', 'tax_claims.old_return_id', '=', 'vat_returns.id')
            ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id')
            ->where('vat_returns.claim_status', '=', TaxClaimStatus::CLAIM)
            ->where('vat_returns.business_location_id', $this->location_id)
            ->where('tax_claims.status', 'approved')
            ->where('tax_credits.payment_status', '!=', 'paid')
            ->orderBy('tax_credits.id')->limit(1)
            ->first();
        return $return;
    }

    public function credit()
    {
        if (!empty($this->claim())) {
            $credit = TaxCredit::query()->findOrFail($this->claim()->credit_id);
            return $credit;
        }
    }

    public function totalPaid()
    {
        if (!empty($this->credit())) {
            $totalPaid = $this->credit()->items->sum('amount');
            return $totalPaid;
        }
    }

    public function creditBroughtForward()
    {
        if (!empty($this->claim())) {
            $remain = $this->claim()->amount - $this->totalPaid();
            if (is_numeric($this->claim()->amount) && $remain > 0) {
                if ($this->claim()->payment_method == 'full') {
                    return $this->claim()->amount;
                } else {
                    $credit = $this->claim()->amount / $this->claim()->installments_count;
                    return $credit;
                }
            } else {
                return 0;
            }
        }
        return 0;
    }

    public function savingClaimPayment($value)
    {
        if ($this->claim() == $value) {
            $items = new TaxCreditItem();
            $items->credit_id = $this->claim()->credit_id;
            $items->return_id = $this->claim()->old_return_id;
            $items->return_type = $this->claim()->old_return_type;
            $items->amount = $this->creditBroughtForward();
            $items->currency = $this->claim()->currency;
            $items->save();

            $this->updateCreditStatus();
        }
    }

    public function updateCreditStatus()
    {
        if ($this->claim()->payment_method == 'full') {
            $payment_status = ['payment_status' => 'paid'];
            $this->credit()->update($payment_status);
        } else {
            if ($this->claim()->amount == $this->totalPaid()) {
                $payment_status = ['payment_status' => 'paid'];
                $this->credit()->update($payment_status);
            } else {
                $payment_status = ['payment_status' => 'paid-partially'];
                $this->credit()->update($payment_status);
            }
        }
    }

    // TODO: This might be entered manually or to be fetched from VFMS (Will Be confirmed)
    public function withHeld()
    {
        foreach ($this->configs as $config) {
            if (in_array($config['code'], ['VWH'])) {
                $value = str_replace(',', '', $config['value']);
                if (is_numeric($value)) {
                    return round($value, 2);
                } else {
                    return 0;
                }
            }
        }
    }

    public function withHeldAndBroughtForward()
    {
        return round($this->creditBroughtForward() - $this->withHeld(), 2);
    }

    public function taxType($code)
    {
        $tax_type = TaxType::query()->where('code', $code)->value('id');
        return $tax_type;
    }

    public function gfs_code($code)
    {
        $tax_type = TaxType::query()->where('code', $code)->value('gfs_code');
        return $tax_type;
    }

    public function compareValues($data, $valueFromUI)
    {
        $total = '';
        foreach ($data as $row) {
            $total = (int)$total + (int)$row['value_excluding_tax'];
        }

        if ($valueFromUI != $total) {
            return true;
        } else {
            return false;
        }
    }

    public function convertToNumber($string)
    {
        $number = str_replace(',','',$string);
        return (int)$number;
    }
}

