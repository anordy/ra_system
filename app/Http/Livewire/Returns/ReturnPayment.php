<?php

namespace App\Http\Livewire\Returns;

use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReturnPayment extends Component
{
    use LivewireAlert, PenaltyTrait, PaymentsTrait;

    public $return;

    public function mount($return){
        $this->return = $return;
    }

    public function refresh(){
        $this->return = get_class($this->return)::find($this->return->id);
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->return->bill);
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect()->route('returns.stamp-duty.show', encrypt($this->return->id));
        }
        $this->alert('error', 'Control number could not be generated, please try again later.');
    }

    public function render(){
        return view('livewire.returns.return-payment');
    }
}