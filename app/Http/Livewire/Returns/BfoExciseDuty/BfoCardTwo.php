<?php

namespace App\Http\Livewire\Returns\BfoExciseDuty;

use App\Enum\CustomMessage;
use App\Models\Returns\BFO\BfoPenalty;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BfoCardTwo extends Component
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
            Log::error('RETURNS-BFO-CARD-TWO', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $penaltyTable  = BfoPenalty::getTableName();
        $returnTable   = BfoReturn::getTableName();
        $filter        = (new BfoReturn())->newQuery();

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
        return view('livewire.returns.bfo-excise-duty.bfo-card-two');
    }
}
