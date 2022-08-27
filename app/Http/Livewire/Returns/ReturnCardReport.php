<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnCardReport extends Component
{
    public $totalTaxAmountUnpaid;
    public $totalLateFilingUnpaid;
    public $totalLatePaymentUnpaid;
    public $totalRateUnpaid;

    public function mount($paidData, $unpaidData){
        
        $this->totalTaxAmountPaid = $paidData['totalTaxAmount'];
        $this->totalLateFilingPaid = $paidData['totalLateFiling'];
        $this->totalLatePaymentPaid = $paidData['totalLatePayment'];
        $this->totalRatePaid = $paidData['totalRate'];

        $this->totalTaxAmountUnpaid = $unpaidData['totalTaxAmount'];
        $this->totalLateFilingUnpaid = $unpaidData['totalLateFiling'];
        $this->totalLatePaymentUnpaid = $unpaidData['totalLatePayment'];
        $this->totalRateUnpaid = $unpaidData['totalRate'];
    }

    public function render()
    {
        return view('livewire.returns.return-card-report');
    }
}
