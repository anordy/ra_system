<?php

namespace App\Http\Livewire\Returns;

use App\Models\BusinessStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ReturnPayment extends Component
{
    use LivewireAlert, PenaltyTrait;

    public $return;

    public function mount($return){
        $this->return = $return;
    }

    public function refresh(){
        $this->return = get_class($this->return)::find($this->return->id);
    }

    public function render(){
        return view('livewire.returns.return-payment');
    }
}