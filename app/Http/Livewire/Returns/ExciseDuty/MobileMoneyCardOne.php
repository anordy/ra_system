<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Returns\MmTransferReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class MobileMoneyCardOne extends Component
{
    use ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $vars;

    public function filterData($data)
    {
        $this->emit('$refresh');
        $this->data = $data;
        self::mount();
    }

    public function mount()
    {
        $returnTable = MmTransferReturn::getTableName();
        $filter      = (new MmTransferReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mobile-money-card-one');
    }
}
