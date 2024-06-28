<?php

namespace App\Http\Livewire\TaxRefund;

use App\Models\TaxRefund\TaxRefund;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TaxRefundPayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $taxRefund;

    public function mount($taxRefund){
        $this->taxRefund = $taxRefund;
    }

    public function refresh(){
        $this->taxRefund = TaxRefund::find($this->taxRefund->id);
        if(is_null($this->taxRefund)){
            abort(404);
        }
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->taxRefund->bill);
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill(){
        try {
            $this->generateTaxRefundControlNumber($this->taxRefund);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('TAX-REFUND-TAX-REFUND-PAYMENT', [$e]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render(){
        return view('livewire.tax-refund.refund-payment');
    }
}