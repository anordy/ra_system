<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use App\Models\TaxType;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class SeaPortCardOne extends Component
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
        $tax = TaxType::where('code', TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE)->first();
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
        return view('livewire.returns.port.sea-port-card-one');
    }
}
