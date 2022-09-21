<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\StampDuty\StampDutyReturnPenalty;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class StampDutyCardTwo extends Component
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
        $penaltyTable  = StampDutyReturnPenalty::getTableName();
        $returnTable   = StampDutyReturn::getTableName();
        $filter        = (new StampDutyReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.stamp-duty.stamp-duty-card-two');
    }
}
