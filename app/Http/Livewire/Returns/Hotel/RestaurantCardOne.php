<?php

namespace App\Http\Livewire\Returns\Hotel;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\TaxType;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class RestaurantCardOne extends Component
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
        $taxType     = TaxType::where('code', TaxType::RESTAURANT)->first();
        $restaurant  = (new HotelReturn())->newQuery();
        $filter      = $restaurant->where('tax_type_id', $taxType->id);
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.hotel.restaurant-card-one');
    }
}
