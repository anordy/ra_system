<?php

namespace App\Http\Livewire\Assesments;

use Livewire\Component;
use App\Traits\CustomAlert;
use App\Traits\PenaltyTrait;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Log;
use App\Services\ZanMalipo\GepgResponse;

class TaxAssessmentPayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $assessment;

    public function mount($assessment)
    {
        $this->assessment = $assessment;
    }

    public function getGepgStatus($code)
    {
        $responseStatus = $this->getResponseCodeStatus($code);

        // Check if 'message' key exists using array_key_exists
        if (array_key_exists('message', $responseStatus)) {
            return $responseStatus['message'];
        } else {
            session()->flash('error', 'something went wrong, please contact your administrator');
            return back();
        }
    }


    public function refresh()
    {
        $this->assessment = get_class($this->assessment)::find($this->assessment->id);
        if (!$this->assessment) {
            session()->flash('error', 'Assessment not found.');
            return redirect()->back()->getTargetUrl();
        }
    }

    public function regenerate()
    {
        if (is_null($this->assessment->bill)) {
            $this->customAlert('error', 'Missing bill information. Please try again.');
            return back(); // Redirect back to the previous page
        }

        $response = $this->regenerateControlNo($this->assessment->bill);
        if ($response) {
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    public function render()
    {
        return view('livewire.assesments.assesment-payment');
    }
}
