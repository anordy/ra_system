<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class MnoCardOne extends Component
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
        $returnTable = MnoReturn::getTableName();
        $filter      = (new MnoReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mno-card-one');
    }
}
