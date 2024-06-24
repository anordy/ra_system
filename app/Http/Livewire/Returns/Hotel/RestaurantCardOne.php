<?php

namespace App\Http\Livewire\Returns\Hotel;

use App\Enum\CustomMessage;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class RestaurantCardOne extends Component
{
    use ReturnFilterTrait, CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $vars;

    public function filterData($data)
    {
        try {
            $this->emit('$refresh');
            $this->data = $data;
            self::mount();
        } catch (\Exception $exception) {
            Log::error('RETURNS-RESTAURANT-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable = HotelReturn::getTableName();
        $taxType     = TaxType::where('code', TaxType::RESTAURANT)->first();
        if (!$taxType) {
            abort(404);
        }
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
