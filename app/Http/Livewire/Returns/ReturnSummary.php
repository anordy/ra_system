<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnSummary extends Component
{
    public $totalSubmittedReturns =[];
    public $totalPaidReturns =[];
    public $totalUnpaidReturns =[];
    public $totalLateFiledReturns =[];
    public $totalLatePaidReturns =[];

    public function mount($vars){
        $this->totalSubmittedReturns = $vars['totalSubmittedReturns'];

        //total paid returns
        $this->totalPaidReturns = $vars['totalPaidReturns'];

        //total unpaid returns
        $this->totalUnpaidReturns = $vars['totalUnpaidReturns'];

        //late filed returns
        $this->totalLateFiledReturns = $vars['totalLateFiledReturns'];
        
        //total late paid returns
        $this->totalLatePaidReturns = $vars['totalLatePaidReturns'];
    }

    public function render()
    {
        return view('livewire.returns.return-summary');
    }
}
