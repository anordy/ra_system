<?php

namespace App\Http\Livewire\PublicService;

use App\Services\ZanMalipo\GepgResponse;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PublicServicePayment extends Component
{
    use CustomAlert, PaymentsTrait, GepgResponse;

    public $return;

    public function mount($return)
    {
        $this->return = $return;
    }

    public function refresh()
    {
        $return = get_class($this->return)::findOrFail($this->return->id);
        if (!$return) {
            session()->flash('error', 'Public Service Return not found.');
            return redirect()->route('public.index');
        }
        $this->return = $return;
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function regenerate()
    {
        $response = $this->regenerateControlNo($this->return->bill);
        if ($response) {
            $this->customAlert('success', __('Your request was submitted, you will receive your payment information shortly.'));
            $this->return = get_class($this->return)::find($this->return->id);
        } else {
            $this->customAlert('error', __('Control number could not be generated, please try again later.'));
        }
    }

    public function generateBill()
    {
        try {
            $this->generatePublicServiceControlNumber($this->return);
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

    public function render()
    {
        return view('livewire.public-service.return-payment');
    }
}
