<?php

namespace App\Http\Livewire\Returns\Port;

use App\Enum\CustomMessage;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\Port\PortReturnPenalty;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display total paid and unpaid amount for tax return in USD and TZS i.e. total tax amount, total late filing
 * total late payment and total interest
 */
class AirPortCardTwo extends Component
{
    use ReturnFilterTrait, CustomAlert;

    protected $listeners = ['filterData' => 'filterData', '$refresh'];
    protected $data;
    public $paidReturnsUSD;
    public $paidReturnsTZS;
    public $unPaidReturnsUSD;
    public $unPaidReturnsTZS;

    public function filterData($data)
    {
        try {
            $this->emit('$refresh');
            $this->data = $data;
            self::mount();
        } catch (\Exception $exception) {
            Log::error('RETURNS-AIRPORT-CARD-TWO', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $tax  = TaxType::select('id')->where('code', TaxType::AIRPORT_SERVICE_SAFETY_FEE)->first();
        if (!$tax) {
            abort(404);
        }

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
        return view('livewire.returns.port.air-port-card-two');
    }
}
