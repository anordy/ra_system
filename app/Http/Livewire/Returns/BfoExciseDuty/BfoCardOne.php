<?php

namespace App\Http\Livewire\Returns\BfoExciseDuty;

use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class BfoCardOne extends Component
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
        $returnTable = BfoReturn::getTableName();
        $filter      = (new BfoReturn)->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.bfo-excise-duty.bfo-card-one');
    }
}
