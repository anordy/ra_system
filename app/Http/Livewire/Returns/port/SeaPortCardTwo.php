<?php

namespace App\Http\Livewire\Returns\Port;

use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\Port\PortReturnPenalty;
use App\Models\TaxType;
use App\Traits\ReturnFilterTrait;
use Livewire\Component;

class SeaPortCardTwo extends Component
{
    use ReturnFilterTrait;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $paidReturnsUSD;
    public $paidReturnsTZS;
    public $unPaidReturnsUSD;
    public $unPaidReturnsTZS;

    public function filterData($data)
    {
        $this->emit('$refresh');
        $this->data = $data;
        self::mount();
    }

    public function mount()
    {
        $tax = TaxType::where('code', TaxType::SEA_SERVICE_TRANSPORT_CHARGE)->first();

        $penaltyTable  = PortReturnPenalty::getTableName();
        $returnTable   = PortReturn::getTableName();
        $filter        = (new PortReturn())->newQuery();
        $filter        = $filter->where('tax_type_id', $tax->id);

        $filter  = $this->dataFilter($filter, $this->data, $returnTable);
        $filter1 = clone $filter;
        $filter2 = clone $filter;

        $USD = $filter1->where($returnTable . '.currency', 'USD');
        $TZS = $filter2->where($returnTable . '.currency', 'TZS');

        $TZS1 = clone $TZS;
        $USD1 = clone $USD;

        $this->paidReturnsUSD = $this->paidReturns($USD, $returnTable, $penaltyTable);
        $this->paidReturnsTZS = $this->paidReturns($TZS, $returnTable, $penaltyTable);

        $this->unPaidReturnsUSD = $this->unPaidReturns($USD1, $returnTable, $penaltyTable);
        $this->unPaidReturnsTZS = $this->unPaidReturns($TZS1, $returnTable, $penaltyTable);
    }
    
    public function render()
    {
        return view('livewire.returns.port.sea-port-card-two');
    }
}
