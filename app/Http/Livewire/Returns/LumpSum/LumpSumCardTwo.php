<?php

namespace App\Http\Livewire\Returns\LumpSum;

use App\Models\Returns\LumpSum\LumpSumPenalties;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class LumpSumCardTwo extends Component
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
        $penaltyTable  = LumpSumPenalties::getTableName();
        $returnTable   = LumpSumReturn::getTableName();
        $filter        = (new LumpSumReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.lump-sum.lump-sum-card-two');
    }
}
