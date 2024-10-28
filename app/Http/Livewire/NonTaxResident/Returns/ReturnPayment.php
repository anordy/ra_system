<?php

namespace App\Http\Livewire\NonTaxResident\Returns;

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

    public function mount($return){
        $this->return = $return;
    }

    public function refresh(){
        try {
            $return = get_class($this->return)::find($this->return->id);
            if (!$return){
                session()->flash('error', __('Return not found.'));
                return redirect()->route('returns.index');
            }
            $this->return = $return;
        } catch (Exception $exception) {
            Log::error('RETURNS-RETURN-PAYMENT-REFRESH', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }

    }

    public function getGepgStatus($code)
    {
        try {
            return $this->getResponseCodeStatus($code)['message'];
        } catch (Exception $exception) {
            Log::error('RETURNS-RETURN-PAYMENT-GET-GEPG-STATUS', [$exception]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function regenerate(){
        try {
            $response = $this->regenerateControlNo($this->return->bill);
            if ($response){
                $this->customAlert('success', __(CustomMessage::RECEIVE_PAYMENT_SHORTLY));
                $this->return = get_class($this->return)::find($this->return->id);
            } else {
                $this->customAlert('error', __(CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER));
            }
        } catch (Exception $exception) {
            Log::error('RETURNS-RETURN-PAYMENT-REGENERATE', [$exception]);
            $this->customAlert('error', __(CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER));
        }

    }

    /**
     * A Safety Measure to Generate a bill that has not been generated when filing return
     */
    public function generateBill(){
        try {
            $this->generateFailedBill($this->return);
            $this->customAlert('success', __(CustomMessage::RECEIVE_PAYMENT_SHORTLY));
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', __(CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER));
            Log::error('RETURNS-RETURN-PAYMENT-GENERATE-BILL', [$e]);
        }

    }

    public function render(){
        return view('livewire.non-tax-resident.returns.return-payment');
    }
}