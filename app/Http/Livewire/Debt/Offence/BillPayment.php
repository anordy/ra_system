<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\OffencePaymentTrait;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BillPayment extends Component
{
    use CustomAlert, PaymentsTrait, OffencePaymentTrait, GepgResponse;

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
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    public function generateBill(){
        try {
            $this->offenceGenerateBill($this->payment);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            $this->customAlert('error', 'Control number could not be generated, please try again later.');
            Log::error($e);
        }
    }

    public function render(){
        return view('livewire.offence.bill');
    }
}