<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use App\Traits\DualControlActivityTrait;
use App\Traits\ValidationTrait;
use App\Traits\VerificationTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;
use Illuminate\Support\Facades\Gate;

class TaxAgentFeeModal extends Component
{
    use LivewireAlert, DualControlActivityTrait, VerificationTrait;

    public $category, $duration, $amount, $currency, $nationality, $old_values, $new_values;

    public function updated($property)
    {
        if ($this->nationality == '1') {
            $this->currency = 'TZS';
        } elseif ($this->nationality == '0') {
            $this->currency = 'USD';
        } else {
            $this->currency = '';
        }
    }

    public function submit()
    {
        if (!Gate::allows('tax-consultant-fee-configuration-add')) {
            abort(403);
        }

        $validate = $this->validate([
            'category' => 'required',
            'amount' => 'required|regex:/^[\d\s,]*$/',
            'duration' => 'required',
            'nationality' => 'required',
            'currency' => 'required'
        ],
            [
                'nationality.required' => 'This field is required',
                'amount.regex' => 'The amount must be an integer',
            ]
        );

        DB::beginTransaction();
        try {
            $this->amount = (int)str_replace(',', '', $this->amount);

            $fee = TaPaymentConfiguration::query()
                ->where('category', '=', $this->category)
                ->where('is_citizen', $this->nationality)
                ->first();

            $this->new_values = [
                'category' => $this->category,
                'duration' => $this->duration,
                'is_citizen' => $this->nationality,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'created_by' => Auth::id(),
            ];
            if ($fee == null) {
                $agent_fee = TaPaymentConfiguration::query()->create($this->new_values);
                // Get ci_payload
                if (!$this->sign($agent_fee)) {
                    throw new Exception('Failed to verify consultant fee.');
                }
                $this->triggerDualControl(get_class($agent_fee), $agent_fee->id, DualControl::ADD, 'adding tax consultant fee for '.$this->category);

            } else {

                $this->old_values = [
                    'tapc_id' => $fee->id,
                    'category' => $fee->category,
                    'duration' => $fee->duration,
                    'is_citizen' => $fee->is_citizen,
                    'amount' => $fee->amount,
                    'currency' => $fee->currency,
                    'created_by' => $fee->created_by,
                ];

                if (!$this->verify($fee)) {
                    throw new Exception('Failed to verify consultant fee.');
                }
                TaPaymentConfigurationHistory::query()->create($this->old_values);

                $fee->delete();

                $agent_fee = TaPaymentConfiguration::query()->create($this->new_values);
                // Get ci_payload
                if (!$this->sign($agent_fee)) {
                    throw new Exception('Failed to verify consultant fee.');
                }

                $this->triggerDualControl(get_class($agent_fee), $agent_fee->id, DualControl::EDIT, 'editing tax consultant fee', json_encode($this->old_values), json_encode($this->new_values));
            }

            DB::commit();
            $this->alert('success', 'Saved successfully');
            return redirect()->route('settings.tax-consultant-fee');
        } catch (\Throwable $exception) {
            Log::error($exception);
            $this->alert('warning', 'Something went wrong, Please contact an admin');
            return redirect()->route('settings.tax-consultant-fee');

        }
    }

    public function render()
    {
        return view('livewire.tax-agent-fee-modal');
    }
}
