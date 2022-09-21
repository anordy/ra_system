<?php

namespace App\Http\Livewire\Returns\Vat;

use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnPenalty;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class VatCardTwo extends Component
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
        $penaltyTable  = VatReturnPenalty::getTableName();
        $returnTable   = VatReturn::getTableName();
        $filter        = (new VatReturn())->newQuery();

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.vat.vat-card-two');
    }
}
