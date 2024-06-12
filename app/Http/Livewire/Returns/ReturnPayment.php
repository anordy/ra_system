<?php

namespace App\Http\Livewire\Returns;

use App\Services\ZanMalipo\GepgResponse;
use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PenaltyTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;

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
        $response = $this->regenerateControlNo($this->return->bill);
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
            $this->generateReturnControlNumber($this->return);
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
        return view('livewire.returns.return-payment');
    }
}
