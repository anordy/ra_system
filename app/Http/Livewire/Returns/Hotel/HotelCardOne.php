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
class HotelCardOne extends Component
{
    use ReturnFilterTrait, CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $vars; // Carry summary of returns data e.g. total filed

    public function filterData($data)
    {
        try {
            $this->emit('$refresh');
            $this->data = $data;
            self::mount();
        } catch (\Exception $exception) {
            Log::error('RETURNS-HOTEL-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable   = HotelReturn::getTableName();
        $taxType       = TaxType::where('code', TaxType::HOTEL)->first();
        if (!$taxType) {
            abort(404);
        }
        $hotel         = (new HotelReturn())->newQuery();
        $filter        = $hotel->where('tax_type_id', $taxType->id);
        $filter        = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars    = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.hotel.hotel-card-one');
    }
}
