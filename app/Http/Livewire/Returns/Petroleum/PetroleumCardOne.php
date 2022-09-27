<?php

namespace App\Http\Livewire\Returns\Petroleum;

use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class PetroleumCardOne extends Component
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
        $returnTable = PetroleumReturn::getTableName();
        $filter      = (new PetroleumReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.petroleum.petroleum-card-one');
    }
}
