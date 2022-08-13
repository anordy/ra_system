<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnCardReport extends Component
{
    public $totalTaxAmount;
    public $totalLateFiling;
    public $totalLatePayment;
    public $totalRate;

    public function mount($data){
        $this->totalTaxAmount = $data['totalTaxAmount'];
        $this->totalLateFiling = $data['totalLateFiling'];
        $this->totalLatePayment = $data['totalLatePayment'];
        $this->totalRate = $data['totalRate'];
    }

    public function render()
    {
        return view('livewire.returns.return-card-report');
    }
}
