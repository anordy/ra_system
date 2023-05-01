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

    public function credit()
    {

        if (!empty($this->claim_data)) {
            $credit = TaxCredit::query()->findOrFail($this->claim_data->credit_id);
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
        if (!empty($this->claim_data)) {
            $remain = $this->claim_data->amount - $this->totalPaid();
            if (is_numeric($this->claim_data->amount) && $remain > 0) {
                if ($this->claim_data->payment_method == 'full') {
                    return $this->claim_data->amount;
                } else {
                    $credit = $this->claim_data->amount / $this->claim_data->installments_count;
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
        if ($this->claim_data->amount == $value) {
            $items = new TaxCreditItem();
            $items->credit_id = $this->claim_data->credit_id;
            $items->return_id = $this->claim_data->old_return_id;
            $items->return_type = $this->claim_data->old_return_type;
            $items->amount = $this->creditBroughtForward();
            $items->currency = $this->claim_data->currency;
            $items->save();

            $this->updateCreditStatus();
        }
    }

    public function updateCreditStatus()
    {
        if ($this->claim_data->payment_method == 'full') {
            $payment_status = ['payment_status' => 'paid'];
            $this->credit()->update($payment_status);
        } else {
            if ($this->claim_data->amount == $this->totalPaid()) {
                $payment_status = ['payment_status' => 'paid'];
                $this->credit()->update($payment_status);
            } else {
                $payment_status = ['payment_status' => 'paid-partially'];
                $this->credit()->update($payment_status);
            }
        }
    }


}

