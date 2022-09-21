<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class PortCardOne extends Component
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
        $returnTable = PortReturn::getTableName();
        $filter      = (new PortReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.port.port-card-one');
    }
}
