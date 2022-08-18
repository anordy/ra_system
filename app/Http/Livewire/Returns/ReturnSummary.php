<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnSummary extends Component
{
    public $totalSubmittedReturns;
    public $totalPaidReturns;
    public $totalUnpaidReturns;
    public $totalLateFiledReturns;
    public $totalLatePaidReturns;
    public $totalInTimeFiledReturns;

    public function mount($vars){
        //All filings
        $this->totalSubmittedReturns = $vars['totalSubmittedReturns'];

        //late filed returns
        $this->totalLateFiledReturns = $vars['totalLateFiledReturns'];

        //In-Time filings
        $this->totalInTimeFiledReturns = $vars['totalInTimeFiledReturns'];

        //total paid returns
        $this->totalPaidReturns = $vars['totalPaidReturns'];

        //total late paid returns
        $this->totalLatePaidReturns = $vars['totalLatePaidReturns'];

        //total unpaid returns
        $this->totalUnpaidReturns = $vars['totalUnpaidReturns'];

    }

    public function render()
    {
        return view('livewire.returns.return-summary');
    }
}
