<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Returns\ExciseDuty\MnoPenalty;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class MnoCardTwo extends Component
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
        $penaltyTable  = MnoPenalty::getTableName();
        $returnTable   = MnoReturn::getTableName();
        $filter        = (new MnoReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mno-card-two');
    }
}
