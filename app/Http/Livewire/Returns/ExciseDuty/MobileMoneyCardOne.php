<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Enum\CustomMessage;
use App\Models\Returns\MmTransferReturn;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class MobileMoneyCardOne extends Component
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
            Log::error('RETURNS-MOBILE-MONEY-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable = MmTransferReturn::getTableName();
        $filter      = (new MmTransferReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mobile-money-card-one');
    }
}
