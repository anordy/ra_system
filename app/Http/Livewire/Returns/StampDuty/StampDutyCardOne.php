<?php

namespace App\Http\Livewire\Returns\StampDuty;

use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class StampDutyCardOne extends Component
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
        $returnTable = StampDutyReturn::getTableName();
        $filter      = (new StampDutyReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.stamp-duty.stamp-duty-card-one');
    }
}
