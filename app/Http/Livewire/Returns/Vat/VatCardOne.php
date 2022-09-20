<?php

namespace App\Http\Livewire\Returns\Vat;

use App\Models\Returns\Vat\VatReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class VatCardOne extends Component
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
        $returnTable = VatReturn::getTableName();
        $filter      = (new VatReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.vat.vat-card-one');
    }
}
