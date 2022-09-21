<?php

namespace App\Http\Livewire\Returns\EmTransaction;

use App\Models\Returns\EmTransactionPenalty;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class EmCardTwo extends Component
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
        $penaltyTable  = EmTransactionPenalty::getTableName();
        $returnTable   = EmTransactionReturn::getTableName();
        $filter        = (new EmTransactionReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.em-transaction.em-card-two');
    }
}
