<?php

namespace App\Http\Livewire;

use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;

class TaxAgentFeeModal extends Component
{
    use LivewireAlert;

    public $category, $duration, $amount, $currency, $nationality;

    public function updated($property)
    {
        if ($this->nationality == '1')
        {
            $this->currency = 'TZS';
        }
        else
        {
            $this->currency = 'USD';
        }
    }

    public function submit()
    {
        $validate = $this->validate([
            'category' => 'required',
            'amount' => 'required|numeric',
            'duration' => 'required',
            'nationality' => 'required',
            'currency' => 'required'
        ],
            [
                'nationality.required' => 'This field is required'
            ]
        );

        DB::beginTransaction();
        try {
            $fee = TaPaymentConfiguration::query()
                ->where('category', '=', $this->category)
                ->where('is_citizen', $this->nationality)
                ->first();
            if ($fee == null) {
                TaxAgentFee::saveFee(
                    $this->category,
                    $this->duration,
                    $this->nationality,
                    $this->amount,
                    $this->currency,
                    Auth::id()
                );
            } else {
                $cat = $fee->category;
                $id = $fee->id;
                $du = $fee->duration;
                $no = $fee->is_citizen;
                $am = $fee->amount;
                $cur = $fee->currency;
                $cr = $fee->created_by;

                $fee->delete();

                TaxAgentFee::saveFee(
                    $this->category,
                    $this->duration,
                    $this->nationality,
                    $this->amount,
                    $this->currency,
                    Auth::id()
                );

                $hist = new TaPaymentConfigurationHistory();
                $hist->tapc_id = $id;
                $hist->category = $cat;
                $hist->duration = $du;
                $hist->is_citizen = $no;
                $hist->amount = $am;
                $hist->currency = $cur;
                $hist->created_by = $cr;
                $hist->save();

            }

            DB::commit();
            $this->flash('success', 'Saved successfully', [], redirect()->back()->getTargetUrl());

        } catch (\Throwable $exception) {
            Log::error($exception);

            $this->flash('warning', 'Internal server error', [], redirect()->back()->getTargetUrl());

        }
    }

    public function render()
    {
        return view('livewire.tax-agent-fee-modal');
    }
}
