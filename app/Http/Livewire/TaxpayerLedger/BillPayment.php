<?php

namespace App\Http\Livewire\TaxpayerLedger;

use App\Enum\CustomMessage;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BillPayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $payment;

    public function mount($payment){
        $this->payment = $payment;
    }

    public function getGepgStatus($code)
    {
        if (isset($this->getResponseCodeStatus($code)['message'])) {
            return $this->getResponseCodeStatus($code)['message'];
        } else {
            return 'Unknown Status';
        }
    }

    public function refresh(){
        $this->payment = get_class($this->payment)::find($this->payment->id);
        if (!$this->payment){
            session()->flash('error', 'Payment not found.');
            return redirect()->back()->getTargetUrl();
        }
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->payment->latestBill);
        if ($response){
            session()->flash('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
    }

    public function generateBill(){
        try {
            $this->generateLedgerControlNumber($this->payment);
            $this->customAlert('success', CustomMessage::RECEIVE_PAYMENT_SHORTLY);
            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            $this->customAlert('error', CustomMessage::FAILED_TO_GENERATE_CONTROL_NUMBER);
            Log::error($e);
        }
    }

    public function render(){
        return view('livewire.taxpayer-ledger.bill');
    }
}