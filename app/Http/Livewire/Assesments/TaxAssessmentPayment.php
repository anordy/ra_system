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

    public function mount($assessment){
        $this->assessment = $assessment;
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function refresh(){
        $this->assessment = get_class($this->assessment)::find($this->assessment->id);
        if (!$this->assessment){
            session()->flash('error', 'Assessment not found.');
            return redirect()->back()->getTargetUrl();
        }
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->assessment->bill);
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        }
        $this->customAlert('error', 'Control number could not be generated, please try again later.');
    }

    public function generateBill(){
        try {
            $this->generateAssessmentControlNumber($this->assessment);
            $this->customAlert('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            $this->customAlert('error', 'Control number could not be generated, please try again later.');
            Log::error($e);
        }
    }

    public function render(){
        return view('livewire.assesments.assesment-payment');
    }
}