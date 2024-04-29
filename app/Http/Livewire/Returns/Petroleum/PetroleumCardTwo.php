<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumPenalty;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class PetroleumCardTwo extends Component
{
    use ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $paidReturnsUSD;
    public $paidReturnsTZS;
    public $unPaidReturnsUSD;
    public $unPaidReturnsTZS;

    public function filterData($data)
    {
        $this->emit('$refresh');
        $this->data = $data;
        self::mount();
    }

    public function mount()
    {
        $penaltyTable  = PetroleumPenalty::getTableName();
        $returnTable   = PetroleumReturn::getTableName();
        $filter        = (new PetroleumReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filter1 = clone $filter;
        $filter2 = $filter1;

        $USD = $filter1->where($returnTable . '.currency', 'USD');
        $TZS = $filter2->where($returnTable . '.currency', 'TZS');

        $TZS1 = clone $TZS;
        $USD1 = clone $USD;

        $this->paidReturnsUSD = $this->paidReturns($USD, $returnTable, $penaltyTable);
        $this->paidReturnsTZS = $this->paidReturns($TZS, $returnTable, $penaltyTable);

        $this->unPaidReturnsUSD = $this->unPaidReturns($USD1, $returnTable, $penaltyTable);
        $this->unPaidReturnsTZS = $this->unPaidReturns($TZS1, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.petroleum.petroleum-card-two');
    }
}
