<?php

namespace App\Http\Livewire\Returns\BfoExciseDuty;

use App\Models\Returns\BFO\BfoPenalty;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class BfoCardTwo extends Component
{
    use ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $paidReturns;
    public $unPaidReturns;

    public function filterData($data)
    {
        $this->emit('$refresh');
        $this->data = $data;
        self::mount();
    }

    public function mount()
    {
        $penaltyTable  = BfoPenalty::getTableName();
        $returnTable   = BfoReturn::getTableName();
        $filter        = (new BfoReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.bfo-excise-duty.bfo-card-two');
    }
}
