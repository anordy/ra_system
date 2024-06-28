<?php

namespace App\Http\Livewire\Returns\ExciseDuty;

use App\Enum\CustomMessage;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\CustomAlert;
use App\Traits\ReturnFilterTrait;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

/**
 * Display summary of returns data i.e. total filed, late filed,
 * in-time filed, paid, unpaid and late paid returns
 */
class MnoCardOne extends Component
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
            Log::error('RETURNS-MNO-CARD-ONE', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function mount()
    {
        $returnTable = MnoReturn::getTableName();
        $filter      = (new MnoReturn())->newQuery();
        $filter      = $this->dataFilter($filter, $this->data, $returnTable);
        $this->vars  = $this->getSummaryData($filter);
    }

    public function render()
    {
        return view('livewire.returns.excise-duty.mno-card-one');
    }
}
