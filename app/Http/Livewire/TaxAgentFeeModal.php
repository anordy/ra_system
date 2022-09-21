<?php

namespace App\Http\Livewire;

use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use App\Traits\ValidationTrait;
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
        elseif($this->nationality == '0')
        {
            $this->currency = 'USD';
        }
        else
        {
            $this->currency = '';
        }
    }

    public function submit()
    {
        $validate = $this->validate([
            'category' => 'required',
            'amount' => 'required|regex:/^[\d\s,]*$/',
            'duration' => 'required',
            'nationality' => 'required',
            'currency' => 'required'
        ],
            [
                'nationality.required' => 'This field is required',
                'amount.regex'=>'The amount must be an integer',
            ]
        );

        DB::beginTransaction();
        try {
            $this->amount = (int)str_replace(',', '', $this->amount);

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

                $hist = new TaPaymentConfigurationHistory();
                $hist->tapc_id = $fee->id;
                $hist->category = $fee->category;
                $hist->duration = $fee->duration;
                $hist->is_citizen = $fee->is_citizen;
                $hist->amount = $fee->amount;
                $hist->currency = $fee->currency;
                $hist->created_by = $fee->created_by;
                $hist->save();

                $fee->delete();

                TaxAgentFee::saveFee(
                    $this->category,
                    $this->duration,
                    $this->nationality,
                    $this->amount,
                    $this->currency,
                    Auth::id()
                );

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
