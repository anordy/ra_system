<?php

namespace App\Http\Livewire\Returns\EmTransaction;

use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class EmCardOne extends Component
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
        $returnTable = EmTransactionReturn::getTableName();
        $filter      = (new EmTransactionReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.em-transaction.em-card-one');
    }
}
