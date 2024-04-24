<?php

namespace App\Http\Livewire\Returns;

use App\Enum\CustomMessage;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ReturnPayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $return;

    public function mount($return)
    {
        $this->return = $return;
    }

    public function refresh()
    {
        $this->return = get_class($this->return)::find($this->return->id);
        if (is_null($this->return)) {
            abort(404);
        }
    }

    public function regenerate()
    {
        try {
            $response = $this->regenerateControlNo($this->return->bill);
            if ($response) {
                session()->flash('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
                return redirect(request()->header('Referer'));
            }
            $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);

        } catch (Exception $exception) {
            Log::error('RETURNS-RETURN-PAYMENT-REGENERATE', [$exception]);
            $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
        }
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill()
    {
        try {
            $this->generateReturnControlNumber($this->return);
            $this->customAlert('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));
        } catch (Exception $exception) {
            $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
            Log::error('RETURNS-RETURN-PAYMENT-GENERATE-BILL', [$exception]);
        }
    }

    public function getGepgStatus($code)
    {
        try {
            return $this->getResponseCodeStatus($code)['message'];
        } catch (Exception $exception) {
            Log::error('RETURNS-RETURN-PAYMENT-GET-GEPG-STATUS', [$exception]);
            $this->customAlert('error', 'Failed to get GEPG status');
        }
    }

    public function render()
    {
        return view('livewire.returns.return-payment');
    }
}