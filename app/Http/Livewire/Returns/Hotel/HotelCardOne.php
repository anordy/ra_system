<?php

namespace App\Http\Livewire\Returns\Hotel;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class HotelCardOne extends Component
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
        $returnTable = HotelReturn::getTableName();
        $filter      = (new HotelReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.hotel.hotel-card-one');
    }
}
