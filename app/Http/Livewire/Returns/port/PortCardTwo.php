<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\Port\PortReturnPenalty;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class PortCardTwo extends Component
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
        $penaltyTable  = PortReturnPenalty::getTableName();
        $returnTable   = PortReturn::getTableName();
        $filter        = (new PortReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.port.port-card-two');
    }
}
