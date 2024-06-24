<?php

namespace App\Http\Livewire\PropertyTax;

use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PropertyTaxPayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $payment;

    public function mount($payment)
    {
        $this->payment = $payment;
    }

    public function refresh()
    {
        $this->payment = get_class($this->payment)::find($this->payment->id);
        if (is_null($this->payment)) {
            abort(404);
        }
    }

    public function regenerate()
    {
        $response = $this->regenerateControlNo($this->payment->bill);
        if ($response) {
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    /**
     * A Safety Measure to Generate a bill that has not been generated
     */
    public function generateBill()
    {
        try {
            $this->generatePropertyTaxControlNumber($this->payment);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (Exception $e) {
            $this->customAlert('error', 'Bill could not be generated, please try again later.');
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function render()
    {
        return view('livewire.property-tax.property-tax-payment');
    }
}
