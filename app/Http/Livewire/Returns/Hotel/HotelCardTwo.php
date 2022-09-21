<?php

namespace App\Http\Livewire\Returns\Hotel;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnPenalty;
use App\Models\TaxType;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class HotelCardTwo extends Component
{
    use ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $paidReturns;
    public $unPaidReturns;

    public function filterData($data)
    {
        $this->emit('$refresh');
        $this->data = $data;
        self::mount();
    }

    public function mount()
    {
        $penaltyTable  = HotelReturnPenalty::getTableName();
        $returnTable   = HotelReturn::getTableName();
        $hotel         = (new HotelReturn())->newQuery();
        $taxType       = TaxType::where('code', TaxType::HOTEL)->first();
        $filter        = $hotel->where('tax_type_id', $taxType->id);

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filters = clone $filter;

        $this->paidReturns   = $this->paidReturns($filter, $returnTable, $penaltyTable);
        $this->unPaidReturns = $this->unPaidReturns($filters, $returnTable, $penaltyTable);
    }

    public function render()
    {
        return view('livewire.returns.hotel.hotel-card-two');
    }
}
