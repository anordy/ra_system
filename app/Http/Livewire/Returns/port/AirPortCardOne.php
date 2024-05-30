<?php

namespace App\Http\Livewire\Returns\Port;

use App\Enum\CustomMessage;
use App\Models\Returns\Port\PortReturn;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class AirPortCardOne extends Component
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
            Log::error('RETURNS-AIRPORT-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $tax  = TaxType::where('code', TaxType::AIRPORT_SERVICE_SAFETY_FEE)->first();
        if (!$tax) {
            abort(404);
        }

        $returnTable = PortReturn::getTableName();
        $filter      = (new PortReturn())->newQuery();
        $filter      = $filter->where('parent', 0);
        $filter      = $filter->where('tax_type_id', $tax->id);
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.port.air-port-card-one');
    }
}
